<?php

namespace App\Models;

use CodeIgniter\Model;

class DeviceLogModel extends Model
{
    protected $table            = 'device_logs';
    protected $primaryKey       = 'id_logs';
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'user_id', 'device_hash', 'ip_address', 'user_agent',
        'platform', 'language', 'timezone', 'screen_width', 'screen_height',
    ];
}