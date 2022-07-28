<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use App\Models\DataStorage;
use App\Models\Post;
use App\Models\Slider;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PhotoController extends Controller
{
  use ResponseStatus;

  public function __construct()
  {
    $this->middleware('can:backend-gallery-list', ['only' => ['index', 'show']]);
    $this->middleware('can:backend-gallery-create', ['only' => ['create', 'store']]);
    $this->middleware('can:backend-gallery-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:backend-gallery-delete', ['only' => ['destroy']]);
  }

  public function index($id)
  {
    $config['page_title'] = "Photo";
    $page_breadcrumbs = [
      ['url' => route('backend.galleries.index'), 'title' => "Photo"],
      ['url' => '#', 'title' => "Isi Foto"],
    ];

    $data = Post::with('datastorage')->find($id);

    return view('backend.photos.index', compact('page_breadcrumbs', 'config', 'data'));
  }

  public function store($id, Request $request)
  {
    $validator = Validator::make($request->all(), [
      'image' => 'image|mimes:jpg,png,jpeg|max:5000',
    ]);

    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        $dimensions = [['1024', '768', 'thumbnail']];
        $post = Post::with('datastorage')->lockForUpdate()->find($id);
        $max = $post->datastorage()->max('sort') + 1 ?? 1;
        $image = isset($request['file']) && !empty($request['file']) ? FileUpload::uploadImage('file', $dimensions) : NULL;
        $dataRequest = new DataStorage();
        $dataRequest->name = $image;
        $dataRequest->type = 'image';
        $dataRequest->sort = $max;

        $post->datastorage()->save($dataRequest);
        DB::commit();
        $response = response()->json($this->responseStore(true, route('backend.galleries.index')));
      } catch (\Throwable $throw) {
        Log::error($throw);
        DB::rollBack();
        $response = response()->json($this->responseStore(false));
      }
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function updateimage(Request $request)
  {
    $validator = Validator::make($request->all(), [
    ]);
    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        foreach ($request['data'] as $key => $item):
          DataStorage::find($item)->update([
            'sort' => ++$key
          ]);
        endforeach;

        DB::commit();
        $response = response()->json($this->responseUpdate(true));
      } catch (\Throwable $throw) {
        Log::error($throw);
        DB::rollBack();
        $response = response()->json($this->responseUpdate(false));
      }
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function destroy($id, $photoId)
  {
    $data = DataStorage::find($photoId);
    $response = response()->json($this->responseDelete(true));
    if ($data->delete()) {
      Storage::disk('public')->delete(["images/original/$data->name", "images/thumbnail/$data->name", "document/$data->name"]);
      $response = response()->json($this->responseDelete(true));
    }
    return $response;
  }

}
