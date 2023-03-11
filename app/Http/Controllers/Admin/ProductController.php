<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index()
    {
        $product = Product::all();
        $product = Product::with('category')->get();
        return view('admin.product.index', compact('product'));
    }

    public function add()
    {
        $category = Category::all();
        return view('admin.product.add', compact('category'));
    }

    public function insert(Request $request)
    {
        $product = new Product();
        $product->cate_id = $request->input('cate_id');
        $product->name = $request->input('name');
        $product->slug = $request->input('slug');
        $product->small_description = $request->input('small_description');
        $product->description = $request->input('description');
        $product->original_price = $request->input('original_price');
        $product->selling_price = $request->input('selling_price');
        $product->qty = $request->input('qty');
        $product->tax = $request->input('tax');
        $product->status = $request->input('status') == TRUE ? '1' : '0';
        $product->trending = $request->input('trending') == TRUE ? '1' : '0';
        $product->meta_title = $request->input('meta_title');
        $product->meta_keyword = $request->input('meta_keyword');
        $product->meta_description = $request->input('meta_description');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            // check file size
            $size = $file->getSize();
            if ($size > 1024 * 1024) {
                return back()->withErrors(['image' => 'File size must not exceed 1 MB']);
            }
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move(public_path('assets/uploads/product'), $filename);
            $product->image = $filename;
        }

        $product->save();
        return redirect('/products')->with('status', 'Product added successfully!');
    }

    public function edit($id)
    {
        $product = Product::find($id);
        return view('admin.product.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $product->name = $request->input('name');
        $product->slug = $request->input('slug');
        $product->small_description = $request->input('small_description');
        $product->description = $request->input('description');
        $product->original_price = $request->input('original_price');
        $product->selling_price = $request->input('selling_price');
        $product->qty = $request->input('qty');
        $product->tax = $request->input('tax');
        $product->status = $request->input('status') == TRUE ? '1' : '0';
        $product->trending = $request->input('trending') == TRUE ? '1' : '0';
        $product->meta_title = $request->input('meta_title');
        $product->meta_keyword = $request->input('meta_keyword');
        $product->meta_description = $request->input('meta_description');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = 'assets/uploads/product/' . $product->image;
            if (File::exists($path)) {
                File::delete($path);
            }
            // check file size
            $size = $file->getSize();
            if ($size > 1024 * 1024) {
                return back()->withErrors(['image' => 'File size must not exceed 1 MB']);
            }
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move(public_path('assets/uploads/product'), $filename);
            $product->image = $filename;
        }

        $product->update();
        return redirect('products')->with('status', 'Product updated successfully!');
    }

    public function delete($id)
    {
        $product = Product::find($id);
        $path = 'assets/uploads/product/' . $product->image;
        if (File::exists($path)) {
            File::delete($path);
        }
        $product->delete();
        return redirect('products')->with('status', 'Product deleted successfully!');
    }
}
