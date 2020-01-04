<?php


namespace App\Http\Controllers;

/**
 * 前端单页路由
 *
 * Class SpaController
 * @package App\Http\Controllers
 */
class SpaController extends BaseController
{
    public function __invoke()
    {
        return view('index');
    }
}
