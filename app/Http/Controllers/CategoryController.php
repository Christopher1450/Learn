<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller {

    public function index(){
        return response()->json(Category::all());
    }

    public function store(Request $request){
        $validated = $request->validate(['name' => 'required|string|max:255']);

        $category = Category::create($validated);

        return response()->json(['data' => $category], 201);
    }

    public function destroy(Category $category){
        $category->delete();

        return response()->json(['message' => 'Kategori dihapus']);
    }
}
