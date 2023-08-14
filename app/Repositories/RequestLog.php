<?php

namespace App\Repositories;

class RequestLog extends BaseRepository
{

    public function create(array $logData = []): void
    {
        $this->model::firstOrCreate(
            ['request_id' => $logData['request_id']],
            [
                'request_method' => $logData['request_method'],
                'request_path' => $logData['request_path'],
                'request_data' => json_encode($logData['request_data']),
                'response_content' => json_encode($logData['response_content'])
            ]
        );
    }
}
