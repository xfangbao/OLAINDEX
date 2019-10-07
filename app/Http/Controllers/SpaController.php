<?php


namespace App\Http\Controllers;


class SpaController extends BaseController
{
    public function __invoke()
    {
        return view('index');
    }

}
