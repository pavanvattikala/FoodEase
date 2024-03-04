<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        // $specials = Category::where('name', 'specials')->first();

        // return view('welcome', compact('specials'));

        return redirect()->route('pos.index');
    }
    public function thankyou()
    {
        return view('thankyou');
    }
}
