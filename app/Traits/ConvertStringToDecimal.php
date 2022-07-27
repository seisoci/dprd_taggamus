<?php
namespace App\Traits;

trait ConvertStringToDecimal{
  public function convertToDecimal($numeric = 0){
    return floatval(str_replace(',', '', $numeric));
  }
}
