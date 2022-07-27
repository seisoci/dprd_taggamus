<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JalinClearing extends Model
{
  use HasFactory;
  public $timestamps = false;

  protected $fillable = [
    'upload_file_document_id',
    'tgl',
    'kode_bank',
    'nama_bank',
    'kewajiban_gross',
    'hak_gross',
    'kewajiban_net',
    'hak_net',
  ];
}
