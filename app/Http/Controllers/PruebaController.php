<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;

class PruebaController extends Controller
{
    public function index()
    {
        $title = "Page for testing";

        $pets = ["Dog", "Cat", "Horse"];

        return view('pruebas.index', array(
            "pets" => $pets,
            "title" => $title,
        ));
    }

    public function testOrm()
    {

        // $posts = Post::all();

        // foreach ($posts as $post) {
        //     echo "<h2>".$post->user->name."</h1>";
        // }

        $categories = Category::all();

        foreach ($categories as $category) {
            echo "<h2>" . $category->name . "</h1>";
        }

        die();
    }
}
