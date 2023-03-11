<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Exists;
use PhpParser\Node\Stmt\Return_;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::all();
        return view('admin.category.index', compact('category'));
    }

    public function add()
    {
        return view('admin.category.add');
    }

    public function insert(Request $request)
    {
        $category = new Category();
        $category->name = $request->input('name');
        $category->slug = $request->input('slug');
        $category->description = $request->input('description');
        $category->status = $request->input('status') == TRUE ? '1' : '0';
        $category->popular = $request->input('popular') == TRUE ? '1' : '0';
        $category->meta_title = $request->input('meta_title');
        $category->meta_keyword = $request->input('meta_keyword');
        $category->meta_descript = $request->input('meta_description');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            // check file size
            $size = $file->getSize();
            if ($size > 1024 * 1024) {
                return back()->withErrors(['image' => 'File size must not exceed 1 MB']);
            }
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move(public_path('assets/uploads/category'), $filename);
            $category->image = $filename;
        }

        $category->save();
        return redirect('/categories')->with('status', 'Category added successfully!');
    }

    public function edit($id)
    {
        $category = Category::find($id);
        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if ($request->hasFile('image')) {
            $path = 'assets/uploads/category/' .$category->image;
            if (File::exists($path)){
                File::delete($path);
            }
            $file = $request->file('image');
            // check file size
            $size = $file->getSize();
            if ($size > 1024 * 1024) {
                return back()->withErrors(['image' => 'File size must not exceed 1 MB']);
            }
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move(public_path('assets/uploads/category'), $filename);
            $category->image = $filename;
        }

        $category->name = $request->input('name');
        $category->slug = $request->input('slug');
        $category->description = $request->input('description');
        $category->status = $request->input('status') == TRUE ? '1' : '0';
        $category->popular = $request->input('popular') == TRUE ? '1' : '0';
        $category->meta_title = $request->input('meta_title');
        $category->meta_keyword = $request->input('meta_keyword');
        $category->meta_descript = $request->input('meta_description');
        $category->update();

        return redirect('categories')->with('status', 'Category Updated Successfully!');
    }

    public function delete($id)
    {
        $category = Category::find($id);
        if ($category->image) {
            $path = 'assets/uploads/category/' .$category->image;
            if (File::exists($path)) {
                File::delete($path);
            }
        }
        // remove from database
        $category->delete();
        return redirect('categories')->with('status', 'Delete category successfully!');
    }
}
