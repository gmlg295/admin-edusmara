<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
    	if (strlen(session('user_id')) < 3 || session('isLoggedIn') === false) {
	    	//echo "invalid";
	        return redirect()->to(base_url())->with('error', "Invalid Credential");
	    }
        // Do something here
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}