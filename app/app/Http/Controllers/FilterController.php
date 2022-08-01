<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FilterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function filters()
    {
        return view('admin.filters');
    }

    public function pullFilter($filter_id)
    {
        // Pull Github issues using the given filter
    }
}
