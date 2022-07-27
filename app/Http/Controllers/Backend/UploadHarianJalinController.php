<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\JalinClearing;
use App\Models\JalinHarian;
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

class UploadHarianJalinController extends Controller
{
  use ConvertStringToDecimal, CarbonFormat, ResponseStatus;

  public function __construct()
  {
    $this->middleware('can:backend-upload-clearing-jalin-list', ['only' => ['index', 'show']]);
    $this->middleware('can:backend-upload-clearing-jalin-create', ['only' => ['create', 'store']]);
    $this->middleware('can:backend-upload-clearing-jalin-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:backend-upload-clearing-jalin-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request)
  {
    $config['page_title'] = "Upload File Harian Jalin";
    $page_breadcrumbs = [
      ['url' => '#', 'title' => "Daftar Upload File Harian Jalin"],
    ];

    if ($request->ajax()) {
      $data = UploadFileDocument::where([
        ['jenis_file', 'jalin'],
        ['jenis_laporan', 'jalin_harian'],
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

    return view('backend.uploadfile.jalinharian.index', compact('config', 'page_breadcrumbs'));
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
        if (Storage::disk('public')->exists("template/jalin/harian/" . $request['file_raw']->getClientOriginalName())) {
          return response()->json($this->responseStore(false, '', 'File Sudah Ada'));
        }

        Storage::disk('public')->putFileAs("template/jalin/harian/", $request['file_raw'], $request['file_raw']->getClientOriginalName());
        $lines = explode("\n", $file);
        $dataRekap = [];
        $kodeReport = NULL;

        $uploadFileDocument = UploadFileDocument::create([
          'file_name' => $request['file_raw']->getClientOriginalName(),
          'location' => 'template/jalin/harian/' . $request['file_raw']->getClientOriginalName(),
          'jenis_file' => 'jalin',
          'jenis_laporan' => 'jalin_harian',
          'tgl_dokumen' => $this->dMYToYmd($request['tgl']),
        ]);
        foreach ($lines as $item):
          if (trim(substr($item, 0, 11)) == "KODE REPORT" && str_contains(trim(substr($item, 14, 4)), '54') || str_contains(trim(substr($item, 14, 4)), '56')){
            $kodeReport = trim(substr($item, 14, 4));
          } elseif (!$kodeReport) {
            continue;
          }

          if (is_numeric(trim(substr($item, 0, 6)))) {
            $dataRekap [] = [
              'upload_file_document_id' => $uploadFileDocument['id'],
              'tgl' => $this->dMYToYmd($request['tgl']),
              'kode_report' => $kodeReport,
              'trx_time' => wordwrap(trim(substr($item, 0, 6)),2, ':', true),
              'trx_tgl' => $this->dmytoYmdNone(trim(substr($item, 10, 6))),
              'kode_terminal'  => trim(substr($item, 20, 17)),
              'no_kartu'  => trim(substr($item, 37, 19)),
              'jenis_message'  => trim(substr($item, 56, 9)),
              'kode_proses'  => trim(substr($item, 65, 10)),
              'nom_transaksi'  => $this->convertToDecimal(trim(substr($item, 75, 15))),
              'kode_kesalahan'  => trim(substr($item, 91, 10)),
              'kode_bank_iss'  => str_contains($kodeReport, '54') ? trim(substr($item, 101, 9)) : NULL,
              'kode_bank_acq'  => str_contains($kodeReport, '56') ? trim(substr($item, 101, 9)) : NULL,
              'no_ref'  => trim(substr($item, 110, 13)),
              'merchant_type'  => trim(substr($item, 123, 9)),
              'kode_retail'  => trim(substr($item, 132, 21)),
              'approval'  => trim(substr($item, 153, 10)),
              'orig_nom'  => trim(substr($item, 164, 11)),
              'fee_iss'   => $this->convertToDecimal(trim(substr($item, 174, 14))),
              'fee_switching'   => $this->convertToDecimal(trim(substr($item, 187, 14))),
              'fee_lbg_svc'   => $this->convertToDecimal(trim(substr($item, 201, 14))),
              'fee_lbg_std'  => $this->convertToDecimal(trim(substr($item, 215, 14))),
              'net_nominal'  => $this->convertToDecimal(trim(substr($item, 229, 14))),
            ];
          }
        endforeach;

        JalinHarian::insert($dataRekap);
        DB::commit();
        $response = response()->json($this->responseStore(true));
      } catch (\Throwable $throw) {
        Storage::disk('public')->delete('template/jalin/harian/' . $request['file_raw']->getClientOriginalName());
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
