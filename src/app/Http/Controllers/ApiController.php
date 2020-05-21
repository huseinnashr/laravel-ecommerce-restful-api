<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponder;

class ApiController extends Controller
{
    use ApiResponder;

    public function __construct()
    {
        $this->middleware('auth:api');
    }
}
