<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PosController extends Controller
{
    //
    public function index()
    {
        return view('pos.pos-index');
    }
}
