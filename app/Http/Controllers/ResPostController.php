<?php

namespace App\Http\Controllers;

use App\Models\ResPost;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\JwtAuth;


class ResPostController extends Controller
{

    public function getResPostByPost($id){

        $respost = ResPost::where("post_id_res",$id)->get();


        return response()->json([

            "status" => "success",
            "respost" => $respost
        ],200);

    }

    public function store(Request $request){

        $json = $request->input("json",null);

        $params = json_decode($json);

        $params_array = json_decode($json, true);

        if(!empty($params_array)){

            $user = $this->getIdentity($request);


            $validate = \Validator::make($params_array,[
                "file_res"=> "required",
                "user_id_res"=> "required",
                "post_id_res" => "required"
            ]);

            if($validate->fails()){
                $data = array(
                    "code"=> 400,
                    "status"=> "error",
                    "message"=> "Validacion fallida en los datos."
                );
            }else{
                $respost = new ResPost();
                $respost->user_id_res = $user->sub;
                $respost->post_id_res = $params->post_id_res;
                $respost->file_res = $params->file_res;

                $respost->save();

                $data = array(
                    "code"=> 200,
                    "status"=> "success",
                    "respost" => $respost
                );

            }
        }else{
            $data = array(
                "code"=> 400,
                "status"=> "error",
                "message"=> "Params array error"
            );
        }


        return response()->json($data, $data['code']);

    }


    // Validate to upload the file

    public function upload(Request $request)
    {
        $file = $request->file('file0');

        $validate = \Validator::make($request->all(), [
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
            \Storage::disk('docs')->put($file_name, \File::get($file));

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
        $isset = \Storage::disk('docs')->exists($filename);

        if ($isset) {

            $file = \Storage::disk('docs')->get($filename);

            return new Response($file, 200);
        } else {
            $data = array(
                'code' => 404,
                'status' => 'error',
                'message' => 'Imagen no existe.'
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
