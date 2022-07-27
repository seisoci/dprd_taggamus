<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\JalinClearing;
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

class UploadClearingJalinController extends Controller
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
    $config['page_title'] = "Upload File Clearing Jalin";
    $page_breadcrumbs = [
      ['url' => '#', 'title' => "Daftar Upload File Clearing Jalin"],
    ];

    if ($request->ajax()) {
      $data = UploadFileDocument::where([
        ['jenis_file', 'jalin'],
        ['jenis_laporan', 'jalin_clearing'],
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

    return view('backend.uploadfile.jalinclearing.index', compact('config', 'page_breadcrumbs'));
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
        if (Storage::disk('public')->exists("template/jalin/clearing/" . $request['file_raw']->getClientOriginalName())) {
          return response()->json($this->responseStore(false, '', 'File Sudah Ada'));
        }

        Storage::disk('public')->putFileAs("template/jalin/clearing/", $request['file_raw'], $request['file_raw']->getClientOriginalName());
        $lines = explode("\n", $file);
        $dataRekap = [];

        $uploadFileDocument = UploadFileDocument::create([
          'file_name' => $request['file_raw']->getClientOriginalName(),
          'location' => 'template/jalin/clearing/' . $request['file_raw']->getClientOriginalName(),
          'jenis_file' => 'jalin',
          'jenis_laporan' => 'jalin_clearing',
          'tgl_dokumen' => $this->dMYToYmd($request['tgl']),
        ]);

        foreach ($lines as $item):
          if (is_numeric(substr($item, 9, 9))) {
            Bank::updateOrCreate([
              'kode_bank' => trim(substr($item, 9, 9))
            ], [
              'bank_name' => trim(substr($item, 20, 35))
            ]);
            $dataRekap [] = [
              'upload_file_document_id' => $uploadFileDocument['id'],
              'tgl' => $this->dMYToYmd($request['tgl']),
              'kode_bank' => trim(substr($item, 9, 9)),
              'nama_bank' => trim(substr($item, 20, 35)),
              'kewajiban_gross' => $this->convertToDecimal(substr($item, 55, 21)),
              'hak_gross' => $this->convertToDecimal(substr($item, 76, 21)),
              'kewajiban_net' => $this->convertToDecimal(substr($item, 97, 21)),
              'hak_net' => $this->convertToDecimal(substr($item, 118, 21)),
            ];
          }
        endforeach;

        JalinClearing::insert($dataRekap);
        DB::commit();
        $response = response()->json($this->responseStore(true));
      } catch (\Throwable $throw) {
        Storage::disk('public')->delete('template/jalin/clearing/' . $request['file_raw']->getClientOriginalName());
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
