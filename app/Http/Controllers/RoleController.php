<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //

    public function index()
    {
        $roles = Rol::where('name','!=','Bibliotecario')->get();

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'roles' => $roles
        ], 200);
    }
}
