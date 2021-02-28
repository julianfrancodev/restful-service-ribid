<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class VerificationController extends Controller
{
    //

    public function verify($user_id, Request $request){
        if(!$request->hasValidSignature()){

            return Redirect::to("http://localhost:4200/?verification=4cc4d88f6c66ab68e21fad5a70b75c69");

        }

        $user = User::findOrFail($user_id);

        if(!$user->hasVerifiedEmail()){
             $user->markEmailAsVerified();
        }


        return Redirect::to('http://localhost:4200/?verification=c1ab208ad4e235e7fb4bd8f688f4feb9');

    }

    public function resend($user_id){

        $user = User::findOrFail($user_id);

        if($user->hasVerifiedEmail()){
            return response()->json([
                'status' => 'Accepted',
                'message'=>'El email ya esta verificado',
                'code' => 202

            ], 200);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'status' => 'Info',
            'message'=>'Email Reenviado Para Verificacion',
            'code' => 200

        ], 200);

    }



}
