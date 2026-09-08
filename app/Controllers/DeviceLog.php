<?php

namespace App\Controllers;

use App\Models\DeviceLogModel;
use CodeIgniter\HTTP\ResponseInterface;

class DeviceLog extends BaseController
{
    public function store(): ResponseInterface
    {
        $input = $this->request->getJSON(true) ?? [];

        $rules = [
            'device_id'     => 'required|string|min_length[16]|max_length[32]',
            'ip_addr'     => 'required|string|min_length[2]|max_length[18]',
            'platform'      => 'permit_empty|string|max_length[100]',
            'language'      => 'permit_empty|string|max_length[20]',
            'timezone'      => 'permit_empty|string|max_length[100]',
            'screen_width'  => 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[10000]',
            'screen_height' => 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[10000]',
        ];

        if (! $this->validateData($input, $rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'ok' => false,
                'device_hash'=> null,
                'errors' => $this->validator->getErrors(),
            ]);
        } 
        // Sesuaikan bila memakai Shield: $userId = auth()->id();
        if($input['ip_addr'] == "" || $input['device_id'] == ""){
            return $this->response->setStatusCode(201)->setJSON(['ok' => false, 'errors' =>'device_id atau ip_addr kosong']);
        }

        $exists = (new DeviceLogModel())->where(['device_hash' => $input['device_id'], 'ip_address' => $input['ip_addr']])->countAllResults() > 0;
        if ($exists) {
            return $this->response->setStatusCode(201)->setJSON(['ok' => true]);
        }

        (new DeviceLogModel())->insert([
            'device_hash'   => $input['device_id'],
            'ip_address'    => $input['ip_addr'] ?? null,
            'user_agent'    => substr((string) $this->request->getUserAgent(), 0, 512),
            'platform'      => $input['platform'] ?? null,
            'language'      => $input['language'] ?? null,
            'timezone'      => $input['timezone'] ?? null,
            'screen_width'  => $input['screen_width'] ?? null,
            'screen_height' => $input['screen_height'] ?? null,
        ]);

        return $this->response->setStatusCode(201)->setJSON(['ok' => true]);
    }
}