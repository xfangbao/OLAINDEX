<?php


namespace App\Http\Controllers\v1\frontend;


use App\Http\Controllers\BaseController;

class DriveController extends BaseController
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
        $this->middleware('token.refresh');
        $this->middleware('onedrive.refresh');
    }

}
