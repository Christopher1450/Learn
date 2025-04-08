<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller {

    public function index()
    {
        $categories = Category::orderBy('name')->get();
        return view('category.index', compact('categories'));
    }

    public function showForm()
    {
        $categories = Category::all();
        return view('category.index', compact('categories'));
    }

    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:20'
        ]);
    
        Category::create($validated);
    
        return redirect()->route('category.index')->with('success', 'Kategori berhasil ditambahkan.');
    }
    
    public function edit(Category $category)
    {
        return view('category.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category->update(['name' => $request->name]);

        return redirect()->route('category.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category){
        $category->delete();

        return redirect()->route('category.index')->with('success', 'Kategori berhasil dihapus');
    }

}
