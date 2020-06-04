<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    public function showPasswordCredentials(Request $request)
    {
        return $request->user();
    }

    public function showClientCredentials()
    {
        return response()->json(['data' => 'ok'], 200);
    }
}
