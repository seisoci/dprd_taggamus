<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PartaiMember;
use Illuminate\Http\Request;

class StaffController extends Controller
{
  public function index(Request $request)
  {
    visitor()->visit();
    $data = PartaiMember::selectRaw('
        `partai_members`.`id`,
        `partai_members`.`name`,
        `komisis`.`name` AS `komisi_name`
      ')
      ->leftJoin('komisis', 'partai_members.komisi_id', '=', 'komisis.id')
      ->orderBy('komisis.name', 'asc')
      ->simplePaginate(10);

    if ($request->ajax()) {
      $view = view('components.frontend.staff-list', compact('data'))->render();
      return [
        'html' => $view
      ];
    }

    return view('frontend.staff', compact('data'));
  }
}
