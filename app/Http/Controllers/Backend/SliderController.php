<?php

namespace App\Http\Controllers\Backend;

use App\Facades\Fileupload;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Slider;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
  use ResponseStatus;

  public function __construct()
  {
    $this->middleware('can:backend-slider-list', ['only' => ['index', 'show']]);
    $this->middleware('can:backend-slider-create', ['only' => ['create', 'store']]);
    $this->middleware('can:backend-slider-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:backend-slider-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request)
  {
    $config['page_title'] = "Sliders";
    $page_breadcrumbs = [
      ['url' => '#', 'title' => "Daftar Sliders"],
    ];

    $data = Slider::orderBy('sort', 'asc')->get();

    return view('backend.slider.index', compact('config', 'page_breadcrumbs', 'data'));
  }

  public function create()
  {
    $config['page_title'] = "Tambah Sliders";
    $page_breadcrumbs = [
      ['url' => route('backend.sliders.index'), 'title' => "Sliders"],
      ['url' => '#', 'title' => "Tambah Sliders"],
    ];
    return view('backend.slider.create', compact('page_breadcrumbs', 'config'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'nullable|string',
      'sub_title' => 'nullable|string',
      'image' => 'image|mimes:jpg,png,jpeg|max:5000',
    ]);

    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        $dimensions = [['1600', '900', 'thumbnail']];
        $image = isset($request['image']) && !empty($request['image']) ? Fileupload::uploadImage('image', $dimensions) : NULL;
        $data = $request->all();
        $data['image'] = $image;

        $max = Slider::max('sort') + 1;
        $data['sort'] = $max;

        Slider::create($data);

        DB::commit();
        $response = response()->json($this->responseStore(true, route('backend.sliders.index')));
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

  public function edit($id)
  {
    $config['page_title'] = "Ubah Sliders";
    $page_breadcrumbs = [
      ['url' => route('backend.sliders.index'), 'title' => "Sliders"],
      ['url' => '#', 'title' => "Ubah Sliders"],
    ];

    $data = Slider::find($id);

    return view('backend.slider.edit', compact('page_breadcrumbs', 'config', 'data'));
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'nullable|string',
      'sub_title' => 'nullable|string',
      'image' => 'image|mimes:jpg,png,jpeg|max:5000',
    ]);

    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        $dimensions = [['1600', '900', 'thumbnail']];
        $data = Slider::findOrFail($id);
        $dataRequest = $request->all();
        $dataRequest['image'] = isset($request['image']) && !empty($request['image']) ? Fileupload::uploadImage('image', $dimensions) : $data['image'];

        $data->update($dataRequest);

        DB::commit();
        $response = response()->json($this->responseStore(true, route('backend.sliders.index')));
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

  public function destroy($id)
  {
    $data = Slider::find($id);
    $response = response()->json($this->responseDelete(true));
    if ($data->delete()) {
      Storage::disk('public')->delete(["images/original/$data->image", "images/thumbnail/$data->image"]);
      $response = response()->json($this->responseDelete(true));
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
          Slider::find($item)->update([
            'sort' => ++$key
          ]);
        endforeach;

        DB::commit();
        $response = response()->json($this->responseUpdate(true));
      } catch (\Throwable $throw) {
        dd($throw);
        Log::error($throw);
        DB::rollBack();
        $response = response()->json($this->responseUpdate(false));
      }
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

}
