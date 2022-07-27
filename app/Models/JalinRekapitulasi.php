<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class JalinRekapitulasi extends Model
{
  use HasFactory;
  public $timestamps = false;

  protected $fillable = [
    'upload_file_document_id',
    'bank_name',
    'acq_jml_trx_purchase',
    'acq_acq_switch_purchase',
    'acq_iss_switch_purchase',
    'acq_iss_purchase',
    'acq_lbg_standard_purchase',
    'acq_lbg_service_purchase',
    'acq_total_fee_purchase',
    'acq_total_nominal_transaksi_purchase',
    'acq_jml_trx_refund',
    'acq_fee_iss_refund',
    'acq_total_nominal_transaksi_refund',
    'iss_jml_trx_purchase',
    'iss_fee_iss_purchase',
    'iss_total_nominal_transaksi_purchase',
    'iss_jml_trx_refund',
    'iss_fee_iss_refund',
    'iss_total_nominal_transaksi_refund',
    'subtotal_gross_kewajiban',
    'subtotal_gross_hak',
    'acq_kewajiban_dispute',
    'acq_hak_dispute',
    'iss_kewajiban_dispute',
    'iss_hak_dispute',
    'subtotal_gross_kewajiban_t',
    'subtotal_gross_hak_u',
    'total_gross_kewajiban_v',
    'total_gross_hak_w',
    'total_net_kewajiban',
    'total_net_hak',
  ];
}
