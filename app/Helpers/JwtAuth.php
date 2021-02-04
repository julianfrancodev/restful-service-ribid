<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class JwtAuth{

    public $key;

    public function __construct(){
        $this->key = 'fj3498fj3983984j9';
    }

    public function signup($email, $password, $getToken = null){
        // TODO find if user exist in db
        $user = User::where([
            'email'=>$email,
            'password'=>$password
        ])->first();
        //TODO validate object
        $signup = false;
        if(is_object($user)){
            $signup = true;
        }
        //TODO generate token

        if($signup){
            $token = array(
              'sub'=> $user->id,
              'email' => $user->email,
              'name' => $user->name,
              'surname'=> $user->surname,
              'image'=> $user->image,
              'role'=> $user->role,
              'iat' => time(),
              'exp' => time() + (7*24*60*60)
            );

            $jwt = JWT::encode($token,$this->key,'HS256');
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);



            if(is_null($getToken)){
                $data = $jwt;
            }else{
                $data = $decoded;
            }

        }else{
            $data = array(
                'status'=> 404,
                'message'=> 'Login Incorrecto'

            );
        }

        //TODO return token

        return $data;
    }

    public function checkToken($jwt, $getIdentity = false){
        $auth = false;
        try {
            $decoded = JWT::decode($jwt, $this->key,['HS256']);
        } catch (\UnexpectedValueException $e) {
            $auth = false;
        }catch(\DomainException $e){
            $auth = false;
        }

        if(!empty($decoded) && is_object($decoded) && isset($decoded->sub)){
            $auth = true;
        }else{
            $auth = false;
        }

        if($getIdentity){
            return $decoded;
        }

        return $auth;
    }


}
