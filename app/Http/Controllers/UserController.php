<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Helpers\JwtAuth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use function PHPUnit\Framework\isEmpty;


class UserController extends Controller
{
    public function pruebas(Request $request)
    {
        return "Accion de prueba UserController";
    }

    public function register(Request $request)
    {
        // TODO get all data from Post

        $json = $request->input('json', null);

        $params = json_decode($json); //object

        $params_array = json_decode($json, true); //array
        if (!empty($params) && !empty($params_array)) {

            // TODO clean data

            $params_array = array_map('trim', $params_array);

            // TODO Validate all fields

            $messages = array(
                'email.regex' => 'Por el momento ofrecemos este servicio a universidades',
                'email.unique' => 'El Email ya ha sido registrado'
            );
            $validate = Validator::make($params_array, [
                'name' => 'required',
                'email' => 'required|email|max:255|unique:users|regex:/(.*).edu\.co$/i',
                'password' => 'required'
            ], $messages);


            if ($validate->fails()) {
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'errors' => $validate->errors()
                );
            } else {

                // TODO cypher the password

                $pwd = hash('sha256', $params_array['password']);
                // TODO Register the user

                $user = new User();
                $user->name = $params_array['name'];
                $user->email = $params_array['email'];
                $user->password = $pwd;

                $user->save();
                $user->sendEmailVerificationNotification();

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Usuario creado.',
                    'user' => $user
                );
            }
        } else {
            $data = array(
                'status' => 'success',
                'code' => 200,
                'message' => 'Datos enviados no son correctos'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function login(Request $request)
    {

        $jwtAuth = new JwtAuth();


        //TODO get data from post
        $json = $request->input('json', null);
        $params = json_decode($json); //object
        $params_array = json_decode($json, true);

        //TODO validate data

        $validate = Validator::make($params_array, [
            'email' => 'required|email',
            'password' => 'required'
        ]);


        if ($validate->fails()) {
            $signup = array(
                'status' => 'error',
                'code' => 404,
                'errors' => $validate->errors()
            );
        } else {

            $user = User::where('email', $params->email)->get();

            if (isset($user[0]) && $user[0]->hasVerifiedEmail()) {
                $pwd = hash('sha256', $params->password);
                $token = $jwtAuth->signup($params->email, $pwd);
                $signup = array(
                    'status' => 'success',
                    "token" => $token,
                    "code" => 200
                );

                if (!empty($params->gettoken)) {
                    $user = $jwtAuth->signup($params->email, $pwd, true);
                    $signup = array(
                        'status' => 'success',
                        "user" => $user,
                        "code" => 200
                    );
                }
            } else {
                $signup = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => "Verifica Tu Email Para Ingresar"
                );
            }

        }

        return response()->json($signup, $signup['code']);
    }

    public function update(Request $request)
    {

        $token = $request->header('Authorization');

        $jwtAuth = new JwtAuth();

        $checkToken = $jwtAuth->checkToken($token);

        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if ($checkToken && !empty($params_array)) {


            //TODO get user indentify

            $user = $jwtAuth->checkToken($token, true);


            //TODO validate user data
            $messages = array(
                'email.regex' => 'Agradecemos su interes en utilizar nuestro sistema. Sin embargo, por el momento ofrecemos este servicio a universidades',
            );
            $validate = Validator::make($params_array, [
                'name' => 'required|alpha',
                'email' => 'required|email|max:255|unique:users|regex:/(.*).edu\.co$/i',
                'password' => 'required'
            ], $messages);


            unset($params_array['id']);
            unset($params_array['role']);
            unset($params_array['password']);
            unset($params_array['created_at']);
            unset($params_array['remember_token']);


            User::where('id', $user->sub)->update($params_array);


            $data = array(
                'code' => 200,
                'status' => 'success',
                'user' => $user,
                'changes' => $params_array
            );
        } else {
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'El usuario no esta identificado'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function upload(Request $request)
    {

        //TODO get all data
        $image = $request->file('file0');

        //TODO validate image

        $validate = Validator::make($request->all(), [
            'file0' => 'required|image|mimes:jpg,jpeg,png,gif'
        ]);

        //TODO save file
        if ($image || !$validate . fails()) {
            $image_name = time() . $image->getClientOriginalName();
            Storage::disk('users')->put($image_name, File::get($image));

            $data = array(
                'code' => 200,
                'status' => 'success',
                'image' => $image_name
            );
        } else {
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'Error al subir el archivo'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function getImage($filename)
    {

        $isset = Storage::disk('users')->get($filename);

        if ($isset) {
            $file = Storage::disk('users')->get($filename);
            return new Response($file, 200);
        } else {
            $data = array(
                'code' => 404,
                'status' => 'error',
                'message' => 'La imagen no existe'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function detail($id)
    {
        $user = User::find($id);

        if (is_object($user)) {
            $data = array(
                'code' => 200,
                'status' => 'success',
                'user' => $user
            );
        } else {
            $data = array(
                'code' => 404,
                'status' => 'error',
                'message' => 'El usuario no existe'
            );
        }

        return response()->json($data, $data['code']);
    }
}
