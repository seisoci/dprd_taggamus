<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JalinKlaim extends Model
{
    use HasFactory;
    protected $fillable = [
      'upload_file_document_id',
      'tgl',
      'jenis',
      'no_report',
      'trx_code',
      'trx_tgl',
      'trx_time',
      'ref_no',
      'trace_no',
      'term_id',
      'no_kartu',
      'kode_iss',
      'kode_acq',
      'marchant_id',
      'marchant_location',
      'marchant_name',
      'nominal',
      'marchant_code',
      'dispute_trans_code',
      'registration_num',
      'dispute_amount',
      'charge_back_fee',
      'fee_return',
      'dispute_net_amount',
    ];
}
