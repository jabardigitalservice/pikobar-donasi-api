<?php

namespace App\Http\Controllers;

use App\Mappers\UserMapper;
use App\Services\Mapper\Facades\Mapper;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function showPasswordCredentials(Request $request)
    {
        return Mapper::single(new UserMapper(), auth()->user(), $request->method());
    }

    public function showClientCredentials(Request $request)
    {
        return Mapper::array(auth()->user()->toArray(), $request->method());
    }
}
