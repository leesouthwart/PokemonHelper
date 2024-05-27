<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BatchController extends Controller
{
    public function create()
    {
        return view('batch.create');
    }
}
