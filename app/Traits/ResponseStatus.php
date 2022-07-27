<?php

namespace App\Traits;


trait ResponseStatus
{
    public function responseStore($status, $redirect = 'reload', $message = NULL): array
    {
        if ($status == true) {
            return [
                'status' => 'success',
                'message' => 'Data berhasil disimpan',
                'redirect' => $redirect
            ];
        }
        return [
            'status' => 'error',
            'message' => $message ?? 'Data gagal dibuat',
        ];
    }

    public function responseUpdate($status, $redirect = 'reload', $message = NULL): array
    {
        if ($status == true) {
            return [
                'status' => 'success',
                'message' => 'Data berhasil diubah',
                'redirect' => $redirect
            ];
        }
        return [
            'status' => 'error',
            'message' => $message ?? 'Data gagal diubah'
        ];
    }

    public function responseDelete($status, $redirect = 'reload'): array
    {
        if ($status == true) {
            return [
                'status' => 'success',
                'message' => 'Data berhasil dihapus',
                'redirect' => $redirect
            ];
        }
        return [
            'status' => 'error',
            'message' => 'Data gagal dihapus'
        ];
    }
}
