<?php

namespace App\Http\Controllers;

use App\Helpers\JwtAuth;
use App\Models\LibDocument;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LibDocumentController extends Controller
{

    public function index()
    {

        $lib_document = LibDocument::orderBy('title')
            ->get();

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'libdocuments' => $lib_document
        ], 200);

    }

    public function getLibDocumentsByUser($id)
    {

        $lib_documents = LibDocument::where('user_id', $id)
            ->with('category')
            ->with('documentType')
            ->paginate(4);

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'libdocuments' => $lib_documents
        ], 200);
    }

    public function store(Request $request)
    {

        $json = $request->input("json", null);

        $params = json_decode($json);

        $params_array = json_decode($json, true);

        if (!empty($params_array)) {

            $user = $this->getIdentity($request);


            $validate = Validator::make($params_array, [
                "user_id" => "required",
                "file_lib" => "required",
                "title" => "required",
                "document_type_id" => "required",
                "category_id" => "required",
                "author" => "required"
            ]);

            if ($validate->fails()) {
                $data = array(
                    "code" => 400,
                    "status" => "error",
                    "message" => "El archivo y todo lo demas es obligatorio"
                );
            } else {
                $lib_document = new LibDocument();
                $lib_document->user_id = $user->sub;
                $lib_document->file_lib = $params->file_lib;
                $lib_document->title = $params->title;
                $lib_document->section = $params->section;
                $lib_document->pages = $params->pages;
                $lib_document->document_type_id = $params->document_type_id;
                $lib_document->category_id = $params->category_id;
                $lib_document->author = $params->author;

                $lib_document->save();

                $data = array(
                    "code" => 200,
                    "status" => "success",
                    "respost" => $lib_document
                );

            }
        } else {
            $data = array(
                "code" => 400,
                "status" => "error",
                "message" => "Params array error"
            );
        }


        return response()->json($data, $data['code']);


    }

    public function upload(Request $request)
    {
        $file = $request->file('file0');

        $validate = Validator::make($request->all(), [
            'file0' => 'required|file|mimes:pdf'
        ]);

        if (!$file || $validate->fails()) {
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'Error al subir el archivo'
            );
        } else {
            $file_name = time() . $file->getClientOriginalName();
            Storage::disk('docs')->put($file_name, File::get($file));
            $data = array(
                'code' => 200,
                'status' => 'success',
                'file' => $file_name
            );
        }

        return response()->json($data, $data['code']);
    }

    private function getIdentity($request)
    {
        $jwtAuth = new JwtAuth();
        $token = $request->header('Authorization');
        $user = $jwtAuth->checkToken($token, true);

        return $user;
    }


}
