<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JalinHarian extends Model
{
    use HasFactory;

    protected $fillable = [
      'upload_file_document_id',
      'tgl',
      'kode_report',
      'trx_time',
      'trx_tgl',
      'kode_terminal',
      'no_kartu',
      'jenis_message',
      'kode_proses',
      'nom_transaksi',
      'kode_kesalahan',
      'kode_bank_iss',
      'kode_bank_acq',
      'no_ref',
      'merchant_type',
      'kode_retail',
      'approval',
      'orig_nom',
      'fee_iss',
      'fee_switching',
      'fee_lbg_svc',
      'fee_lbg_std',
      'net_nominal',
    ];
}
