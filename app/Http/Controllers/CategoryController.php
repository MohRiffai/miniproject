<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    Public Function index()
    {
        return view(view: 'categories.index');
    }
}
