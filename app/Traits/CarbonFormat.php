<?php
namespace App\Traits;

use Carbon\Carbon;

trait CarbonFormat{
  public function dMYToYmd($tgl){
    return Carbon::createFromFormat('d M Y', $tgl)->format('Y-m-d');
  }

  public function dmytoYmdslash($tgl){
    return Carbon::createFromFormat('d/m/y', $tgl)->format('Y-m-d');
  }

  public function dmytoYmdNone($tgl){
    return Carbon::createFromFormat('ymd', $tgl)->format('Y-m-d');
  }

}
