<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class UploadFileDocument extends Model
{
  use HasFactory;

  protected $fillable =[
    'file_name',
    'location',
    'jenis_file',
    'jenis_laporan',
    'tgl_dokumen',
  ];

}
