<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Category;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('api.auth', ['except' => ['index', 'show',]]);
    }

    public function index()
    {
        $categories = Category::all();
        return response()->json([
            'categories' => $categories,
            'status' => 'success',
            'code' => 200

        ], 200);
    }

    public function show($id)
    {
        $category = Category::find($id);

        if (is_object($category)) {
            $data = array(
                "code" => 200,
                "status" => 'success',
                "category" => $category
            );
        } else {
            $data = array(
                "code" => 404,
                "status" => 'error',
                "message" => 'Categoria no encontrada'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function store(Request $request)
    {
        $json = $request->input('json', null);
        $array_params = json_decode($json, true);

        $validate = \Validator::make($array_params, [
            'name' => 'required'
        ]);

        if ($validate->fails()) {
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'No se ha guardado la categoria'
            );
        } else {
            $category = new Category();
            $category->name = $array_params['name'];
            $category->save();

            $data = array(
                'code' => 200,
                'status' => 'success',
                'category' => $category
            );
        }

        return response()->json($data, $data['code']);
    }

    public function update($id, Request $request)
    {
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);



        if (!empty($params_array)) {
            $validate = \Validator::make($params_array, [
                'name' => 'required'
            ]);


            unset($params_array['id']);
            unset($params_array['created_at']);

            $category = Category::where('id', $id)->update($params_array);

            $data = array(
                'code' => 200,
                'status' => 'success',
                'category' => $params_array
            );
        } else {
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'No se actualizado la categoria'
            );
        }

        return response()->json($data, $data['code']);
    }
}
