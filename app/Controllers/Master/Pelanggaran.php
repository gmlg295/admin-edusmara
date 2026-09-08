<?php

namespace Master\App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\DeviceLogModel;

class Pelanggaran extends Controller
{
    protected $session;
    protected $db;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->session = \Config\Services::session();
        //$this->db      = \Config\Database::connect();
        //$this->session->set(['isLoggedIn'=>false]);
    }

    public function index()
    {
        return view('content/login', [
            'title' => 'Dashboard Admin - EDUSMARA',
            'load_js' => 'login.js',
        ]);
    }

}