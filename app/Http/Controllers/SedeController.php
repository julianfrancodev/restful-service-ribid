<?php


namespace App\Http\Controllers;


use App\Models\Sede;

class SedeController extends Controller
{
    public function index()
    {
        $sedes = Sede::all();
        return response()->json([
            'sedes' => $sedes,
            'status' => 'success',
            'code' => 200
        ], 200);
    }
}
