<?php

namespace App\Http\Controllers;

use App\Events\RespostPublished;
use App\Models\Post;
use App\Models\ResPost;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\JwtAuth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class ResPostController extends Controller
{

    public function getResPostByPost($id)
    {

        $respost = ResPost::where("post_id_res", $id)->with('libDocument')->get();


        return response()->json([

            "status" => "success",
            "respost" => $respost
        ], 200);

    }

    public function getPostByAdminResPost($userId)
    {

        $respost = ResPost::where("user_id_res", $userId)->with('post','post.category')->paginate(4);

        return response()->json([

            "status" => "success",
            "respost" => $respost
        ], 200);
    }

    public function getCountPostsByAdminRepost($userId){
        $respost = ResPost::where("user_id_res", $userId)->with('post','post.category')->count();
        return response()->json([
            "status" => "success",
            "respost" => $respost
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
                "user_id_res" => "required",
                "post_id_res" => "required"
            ]);

            if ($validate->fails()) {
                $data = array(
                    "code" => 400,
                    "status" => "error",
                    "message" => "Validacion fallida en los datos."
                );
            } else {
                $respost = new ResPost();
                $respost->user_id_res = $user->sub;
                $respost->post_id_res = $params->post_id_res;
                $respost->file_res = $params->file_res;
                $respost->lib_document_id = $params->lib_document_id;

                Post::where("id", $params->post_id_res)->update(array('status' => 'COMPLETO'));

                $post = Post::findOrFail($respost->post_id_res);

                $respost->save();

                event(new RespostPublished($post));

                $data = array(
                    "code" => 200,
                    "status" => "success",
                    "respost" => $respost
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


    public function update(Request $request)
    {

        $token = $request->header('Authorization');

        $jwtAuth = new JwtAuth();

        $checkToken = $jwtAuth->checkToken($token);

        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        $params = json_decode($json);

        unset($params_array['id']);
        unset($params_array['created_at']);


        if ($checkToken && !empty($params_array)) {

            $validate = Validator::make($params_array, [
                "user_id_res" => "required",
                "post_id_res" => "required"
            ]);

            if (!$validate->fails()) {

                $respost = ResPost::where('post_id_res', $params->post_id_res)->update($params_array);
                $updated_respost = ResPost::where("post_id_res", $params->post_id_res)->with('libDocument')->first();

                $data = array(
                    'code' => 200,
                    'status' => 'success',
                    'respost' => $respost,
                    'changes' => $updated_respost
                );

            } else {
                $data = array(
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'Fallo en la validacion de los datos'
                );
            }

        } else {
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'Usuario no autenticado o json invalido'
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


    // Function that provides file localted in framework storage

    public function getFile($filename)
    {
        $isset = Storage::disk('docs')->exists($filename);

        if ($isset) {

            $file = Storage::disk('docs')->get($filename);

            return new Response($file, 200);
        } else {
            $data = array(
                'code' => 404,
                'status' => 'error',
                'message' => 'El Documento no existe.'
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
