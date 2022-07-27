<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\JalinKlaim;
use App\Models\UploadFileDocument;
use App\Traits\CarbonFormat;
use App\Traits\ConvertStringToDecimal;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UploadKlaimJalinController extends Controller
{
  use ConvertStringToDecimal, CarbonFormat, ResponseStatus;

  public function __construct()
  {
    $this->middleware('can:backend-upload-klaim-jalin-list', ['only' => ['index', 'show']]);
    $this->middleware('can:backend-upload-klaim-jalin-create', ['only' => ['create', 'store']]);
    $this->middleware('can:backend-upload-klaim-jalin-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:backend-upload-klaim-jalin-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request)
  {
    $config['page_title'] = "Upload File Klaim Jalin";
    $page_breadcrumbs = [
      ['url' => '#', 'title' => "Daftar Upload File Klaim Jalin"],
    ];

    if ($request->ajax()) {
      $data = UploadFileDocument::where([
        ['jenis_file', 'jalin'],
        ['jenis_laporan', 'jalin_klaim'],
      ]);
      return DataTables::of($data)
        ->addColumn('action', function ($row) {
          $actionBtn = '<div class="dropdown">
                          <button type="button" class="btn btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
                            Aksi <i class="fa-regular fa-down"></i>
                          </button>
                          <ul class="dropdown-menu">
                            <li><a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="dropdown-item">Hapus</a></li>
                          </ul>
                        </div> ';
          return $actionBtn;
        })
        ->make(true);
    }

    return view('backend.uploadfile.jalinklaim.index', compact('config', 'page_breadcrumbs'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'tgl' => 'required|date:d M Y',
      'file_raw' => 'required|mimes:txt,text',
    ]);

    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        $file = File::get($request['file_raw']);
        if (Storage::disk('public')->exists("template/jalin/klaim/" . $request['file_raw']->getClientOriginalName())) {
          return response()->json($this->responseStore(false, '', 'File Sudah Ada'));
        }
        Storage::disk('public')->putFileAs("template/jalin/klaim/", $request['file_raw'], $request['file_raw']->getClientOriginalName());
        $lines = explode("\n", $file);
        $dataRekap = [];

        $uploadFileDocument = UploadFileDocument::create([
          'file_name' => $request['file_raw']->getClientOriginalName(),
          'location' => 'template/jalin/klaim/' . $request['file_raw']->getClientOriginalName(),
          'jenis_file' => 'jalin',
          'jenis_laporan' => 'jalin_klaim',
          'tgl_dokumen' => $this->dMYToYmd($request['tgl']),
        ]);;
        $noReport = NULL;

        foreach ($lines as $item):
          if (trim(substr($item, 0, 9)) == "No Report") {
            $noReport = trim(substr($item, 13, 8));
          } elseif (!$noReport) {
            continue;
          }

          if (is_numeric(trim(substr($item, 0, 3)))) {
            $jenisReport = match ($noReport) {
              'DSPT01' => 'acq',
              'DSPT02' => 'iss',
            };
            $dataRekap [] = [
              'upload_file_document_id' => $uploadFileDocument['id'],
              'tgl' => $this->dMYToYmd($request['tgl']),
              'jenis' => $jenisReport,
              'no_report' => $noReport,
              'trx_code' => trim(substr($item, 5, 8)),
              'trx_tgl' => $this->dmytoYmdslash(trim(substr($item, 16, 8))),
              'trx_time' => trim(substr($item, 28, 8)),
              'ref_no' => trim(substr($item, 37, 12)),
              'trace_no' => trim(substr($item, 50, 8)),
              'term_id' => trim(substr($item, 61, 17)),
              'no_kartu' => trim(substr($item, 78, 20)),
              'kode_iss' => trim(substr($item, 98, 12)),
              'kode_acq' => trim(substr($item, 110, 12)),
              'marchant_id' => trim(substr($item, 122, 16)),
              'marchant_location' => trim(substr($item, 138, 28)),
              'marchant_name' => trim(substr($item, 167, 29)),
              'nominal' => $this->convertToDecimal(trim(substr($item, 197, 15))),
              'marchant_code' => trim(substr($item, 213, 21)),
              'dispute_trans_code' => trim(substr($item, 234, 21)),
              'registration_num' => trim(substr($item, 255, 18)),
              'dispute_amount' => $this->convertToDecimal(trim(substr($item, 273, 19))),
              'charge_back_fee' => $this->convertToDecimal(trim(substr($item, 292, 19))),
              'fee_return' => $this->convertToDecimal(trim(substr($item, 311, 18))),
              'dispute_net_amount' => $this->convertToDecimal(trim(substr($item, 329, 21))),
            ];
          }
        endforeach;
        JalinKlaim::insert($dataRekap);
        DB::commit();
        $response = response()->json($this->responseStore(true));
      } catch (\Throwable $throw) {
        Storage::disk('public')->delete('template/jalin/klaim/' . $request['file_raw']->getClientOriginalName());
        Log::error($throw);
        DB::rollBack();
        $response = response()->json($this->responseStore(false));
      }
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function destroy($id)
  {
    try {
      $data = UploadFileDocument::find($id);
      $response = response()->json($this->responseDelete(false));
      if ($data->delete()) {
        Storage::disk('public')->delete($data['location']);
        $response = response()->json($this->responseDelete(true));
      }
    } catch (\Throwable $throw) {
      Log::error($throw);
      DB::rollBack();
      $response = response()->json($this->responseStore(false));
    }
    return $response;
  }
}
