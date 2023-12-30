<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;

class PosController extends Controller
{
    //
    public function index()
    {
        $categoriesWithMenus = Category::with('menus')->get();

        return view('pos.pos-index', compact('categoriesWithMenus'));
    }
}