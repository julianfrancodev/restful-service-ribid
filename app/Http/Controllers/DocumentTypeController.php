<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use Illuminate\Http\Request;

class DocumentTypeController extends Controller
{

    public function index(){

        $document_type = DocumentType::all();

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'document_types' => $document_type
        ], 200);
    }
}
