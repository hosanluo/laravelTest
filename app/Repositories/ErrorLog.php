<?php

namespace App\Repositories;

class ErrorLog extends BaseRepository
{

    public function create(array $logData = []): void
    {
        $this->model::create(
            [
                'error_code' => $logData['code'],
                'http_code' => $logData['status_code'],
                'error_msg' => json_encode($logData['msg']),
            ]
        );
    }
}
