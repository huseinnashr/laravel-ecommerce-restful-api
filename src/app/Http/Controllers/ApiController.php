<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponder;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

class ApiController extends Controller
{
    use ApiResponder;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    protected function allowedAdminAction(){
        if (Gate::denies('admin-action')) {
            throw new AuthorizationException('This actions is unauthorized');
        }
    }
}
