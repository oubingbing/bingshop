<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/10 0010
 * Time: 15:07
 */
class IndexController extends Controller
{
    public function index()
    {
        return view("frontend.index");
    }

}