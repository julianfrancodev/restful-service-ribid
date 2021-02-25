<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    //

    public function verify($user_id, Request $request){
        if(!$request->hasValidSignature()){
            return response()->json([
                'status' => 'error',
                'message'=>'Email de verificacion invalido',
                'code' => 400

            ], 200);
        }

        $user = User::findOrFail($user_id);

        if(!$user->hasVerifiedEmail()){
             $user->markEmailAsVerified();
        }

        return  response()->json([
            'status' => 'success',
            'message'=>'Email Verificado Con Exito',
            'code' => 200

        ], 200);
    }

    public function resend($user_id){

        $user = User::findOrFail($user_id);

        if($user->hasVerifiedEmail()){
            return response()->json([
                'status' => 'info',
                'message'=>'El email ya esta verificado',
                'code' => 400

            ], 200);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'status' => 'info',
            'message'=>'Email Reenviado Para Verificacion',
            'code' => 200

        ], 200);
    }

}
