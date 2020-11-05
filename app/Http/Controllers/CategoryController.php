<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{

    public function show(Request $request)
    {
        $categories = Category::where('commerce_id', $request ->commerce_id)->get();
        return response()->json(['statusCode' => 201,'data' => $categories]);
    }

    public function new(Request $request)
    {
        $category = Category::firstOrCreate ($request->all());

        return response()->json([
            'statusCode' => 201,
            'message' => 'Update category correctly',
            'data'=> ['id'=>$category->id, 'name'=>$category->name, 'commerce_id'=>(int)$category->commerce_id], "type"=>$category->type,
        ]);
    }
}
