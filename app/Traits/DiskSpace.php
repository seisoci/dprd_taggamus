<?php

namespace App\Traits;
use Illuminate\Support\Facades\DB;

trait DiskSpace
{
  public static function diskpace()
  {
    $disktotal = disk_total_space('/'); //DISK usage
    $disktotalsize = $disktotal / 1073741824;

    $diskfree = disk_free_space('/');
    $used = $disktotal - $diskfree;

    $diskusedize = $used / 1073741824;
    $diskuse1 = round(100 - (($diskusedize / $disktotalsize) * 100));
    $diskuse = round(100 - ($diskuse1)) . '%';

    return [
      'diskUse' => $diskuse,
      'diskTotalSize' => $disktotalsize,
      'diskUsedSize' => $diskusedize,
    ];
  }

  public static function getDBSizeInMB()
  {
    $result = DB::select(DB::raw('SELECT table_name AS "Table",
                ((data_length + index_length) / 1024 / 1024) AS "Size"
                FROM information_schema.TABLES
                WHERE table_schema ="'.env('DB_DATABASE', 'forge'). '"
                ORDER BY (data_length + index_length) DESC'));
    $size = array_sum(array_column($result, 'Size'));
    $db_size = number_format((float)$size, 2, '.', '');
    return $db_size;
  }
}
