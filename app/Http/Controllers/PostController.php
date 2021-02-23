<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Post;
use App\Helpers\JwtAuth;
use Illuminate\Support\Facades\Validator;
use function PHPUnit\Framework\isEmpty;


class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth', ['except' => [
            'index',
            'show',
            'getImage',
            'getPostsByCategory',
            'getPostsByUser',
            'getRandomPosts',
            'pagination',
            'getPendingPost',
            'getPostByUser',
            'getPostsBySearch'
        ]]);
    }

    public function index()
    {
        $posts = Post::inRandomOrder()->with("category")->paginate(4);

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'posts' => $posts
        ], 200);
    }

    public function getRandomPosts()
    {
        $posts = Post::inRandomOrder()->limit(2)->get()->load("category");

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'posts' => $posts
        ], 200);

    }

    public function getPendingPost()
    {
        $posts = Post::where("status", "PENDIENTE")->with("category")->paginate(4);

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'posts' => $posts
        ], 200);
    }

    public function show($id)
    {
        $post = Post::find($id)->load('category');

        if (is_object($post)) {
            $data = array(
                'code' => 200,
                'status' => 'success',
                'post' => $post
            );
        } else {
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'El post no existe'
            );
        }
        return response()->json($data, $data['code']);
    }

    public function store(Request $request)
    {
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        if (!empty($params_array)) {
            $user = $this->getIdentity($request);

            $validate = Validator::make($params_array, [
                'title' => 'required',
                'category_id' => 'required',
            ]);

            if ($validate->fails()) {
                $data = array(
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'Validacion fallida en los datos'
                );
            } else {
                $post = new Post();
                $post->user_id = $user->sub;
                $post->category_id = $params->category_id;
                $post->title = $params->title;
                $post->save();

                $data = array(
                    'code' => 200,
                    'status' => 'success',
                    'post' => $post
                );
            }
        } else {
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'Envia los datos correctos.'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function update($id, Request $request)
    {
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if (!empty($params_array)) {


            $validate = Validator::make($params_array, [
                'title' => 'required',
                'content' => 'required',
                'category_id' => 'required'
            ]);

            if (!$validate->fails()) {

                unset($params_array['id']);
                unset($params_array['user_id']);
                unset($params_array['created_at']);
                unset($params_array['user']);

                $user = $this->getIdentity($request);

                $post = Post::where('id', $id)->where('user_id', $user->sub)->first();

                if (!empty($post) && is_object($post)) {

                    $post->update($params_array);

                    $data = array(
                        'code' => 200,
                        'status' => 'success',
                        'post' => $params_array
                    );
                }
            } else {
                $data = array(
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'Validacion de campos fallo'
                );
            }
        } else {
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'No hay datos enviados'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function destroy($id, Request $request)
    {

        $user = $this->getIdentity($request);

        $post = Post::where('id', $id)->where('user_id', $user->sub)->first();


        if (!empty($post)) {

            $post->delete();

            $data = array(
                'code' => 200,
                'status' => 'success',
                'post' => $post

            );
        } else {
            $data = array(
                'code' => 404,
                'status' => 'error',
                'message' => 'El post no existe.'
            );
        }

        return response()->json($data, $data['code']);
    }


    public function getPostsByCategory($id)
    {
        $posts = Post::where('category_id', $id)->with("category")->paginate(4);

        return response()->json([
            'status' => 'success',
            'posts' => $posts
        ]);
    }

    public function getPostsByUser($id)
    {
        $posts = Post::where('user_id', $id)->with("category")->paginate(4);

        return response()->json([
            'status' => 'success',
            'posts' => $posts
        ], 200);
    }

    public function getPostsBySearch(Request $request){

        $search = $request->input('search', null);

        $posts = Post::where('title','ilike',"%$search%")->limit(6)->get();

        return response()->json([
            'status' => 'success',
            'posts' => $posts
        ], 200);

    }

    private function getIdentity($request)
    {
        $jwtAuth = new JwtAuth();
        $token = $request->header('Authorization');
        $user = $jwtAuth->checkToken($token, true);

        return $user;
    }
}
