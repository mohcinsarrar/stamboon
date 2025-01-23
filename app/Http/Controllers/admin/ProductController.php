<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\DataTables\ProductsDataTable;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class ProductController extends Controller
{
  public function index(ProductsDataTable $dataTable)
  {
    return $dataTable->render('admin.products.index');
  }


  public function store(Request $request){
    
    $inputs = $request->except('_token');

    Validator::make($inputs, [
      'name' => 'required|string|unique:products,name',
      'price' => 'required|numeric|min:0',
      'image' => ['required',File::image()],
      'description' => 'required|string',
      'duration' => 'required|numeric|min:0',
      'print_number' => 'required|numeric|min:0',
      'chart_type' => 'required|array|min:1',
      'chart_type.*' => 'required|string|in:fantree,pedigree',
      
      'fantree_max_generation' => 'required|numeric|min:0',
      'pedigree_max_generation' => 'required|numeric|min:0',

      'max_nodes' => 'required|numeric|min:0',

      'fantree_print_type' => 'nullable|array|min:0',
      'fantree_print_type.*' => 'nullable|string|in:png,pdf',

      'pedigree_print_type' => 'nullable|array|min:0',
      'pedigree_print_type.*' => 'nullable|string|in:png,pdf',

      'fantree_max_output_png' => 'required|string|in:1,2,3,4,5',
      'fantree_max_output_pdf' => 'required|string|in:a0,a1,a2,a3,a4',

      'pedigree_max_output_png' => 'required|string|in:1,2,3,4,5',
      'pedigree_max_output_pdf' => 'required|string|in:a0,a1,a2,a3,a4',

    ])->validate();

    // chart type
    if (in_array("fantree", $inputs['chart_type'])) {
      $fantree = true;
    }
    else{
      $fantree = false;
    }

    if (in_array("pedigree", $inputs['chart_type'])) {
      $pedigree = true;
    }
    else{
      $pedigree = false;
    }

    // fantree print type
    if($request->fantree_print_type != null){
      if (in_array("png", $inputs['fantree_print_type'])) {
        $fantree_png = true;
      }
      else{
        $fantree_png = false;
      }

      if (in_array("pdf", $inputs['fantree_print_type'])) {
        $fantree_pdf = true;
      }
      else{
        $fantree_pdf = false;
      }
    }
    else{
      $fantree_png = false;
      $fantree_pdf = false;
    }

      // pedigree print type
    if($request->pedigree_print_type != null){
      if (in_array("png", $inputs['pedigree_print_type'])) {
        $pedigree_png = true;
      }
      else{
        $pedigree_png = false;
      }

      if (in_array("pdf", $inputs['pedigree_print_type'])) {
        $pedigree_pdf = true;
      }
      else{
        $pedigree_pdf = false;
      }
    }
    else{
      $pedigree_png = false;
      $pedigree_pdf = false;
    }
    
    $image = null;
    if($request->hasFile('image') && $request->file('image')->isValid()) {
      
      // store the new image
      $image = $request->file('image')->store('products');
    }

    Product::create([
      'name' => $inputs['name'],
      'description' => $inputs['description'],
      'image' => $image,
      'fantree' => $fantree,
      'pedigree' => $pedigree,
      'duration' => $inputs['duration'],
      'print_number' => $inputs['print_number'],
      'price' => $inputs['price'],

      'fantree_max_generation' => $inputs['fantree_max_generation'],
      'pedigree_max_generation' => $inputs['pedigree_max_generation'],

      'max_nodes' => $inputs['max_nodes'],

      'fantree_max_output_png' => $inputs['fantree_max_output_png'],
      'fantree_max_output_pdf' => $inputs['fantree_max_output_pdf'],

      'pedigree_max_output_png' => $inputs['pedigree_max_output_png'],
      'pedigree_max_output_pdf' => $inputs['pedigree_max_output_pdf'],


      'fantree_output_png' => $fantree_png,
      'fantree_output_pdf' => $fantree_pdf,

      'pedigree_output_png' => $pedigree_png,
      'pedigree_output_pdf' => $pedigree_pdf,

    ]);

    return redirect()->back()->with('success','Product created !!');
  }


  public function update(Request $request, $id){

    $product = Product::findOrFail($id);

    $inputs = $request->except(['_token','_method']);

    Validator::make($inputs, [
      'name' => 'required|string|unique:products,name,'.$id,
      'price' => 'required|numeric|min:0',
      'image' => ['nullable',File::image()],
      'description' => 'required|string',
      'duration' => 'required|numeric|min:0',
      'print_number' => 'required|numeric|min:0',
      'chart_type' => 'required|array|min:1',
      'chart_type.*' => 'required|string|in:fantree,pedigree',
      
      'fantree_max_generation' => 'required|numeric|min:0',
      'pedigree_max_generation' => 'required|numeric|min:0',

      'max_nodes' => 'required|numeric|min:0',

      'fantree_print_type' => 'nullable|array|min:0',
      'fantree_print_type.*' => 'nullable|string|in:png,pdf',

      'pedigree_print_type' => 'nullable|array|min:0',
      'pedigree_print_type.*' => 'nullable|string|in:png,pdf',

      'fantree_max_output_png' => 'required|string|in:1,2,3,4,5',
      'fantree_max_output_pdf' => 'required|string|in:a0,a1,a2,a3,a4',

      'pedigree_max_output_png' => 'required|string|in:1,2,3,4,5',
      'pedigree_max_output_pdf' => 'required|string|in:a0,a1,a2,a3,a4',
    ])->validate();

     // chart type
     if (in_array("fantree", $inputs['chart_type'])) {
      $fantree = true;
    }
    else{
      $fantree = false;
    }

    if (in_array("pedigree", $inputs['chart_type'])) {
      $pedigree = true;
    }
    else{
      $pedigree = false;
    }

    // fantree print type
    if($request->pedigree_print_type != null){
      if (in_array("png", $inputs['fantree_print_type'])) {
        $fantree_png = true;
      }
      else{
        $fantree_png = false;
      }

      if (in_array("pdf", $inputs['fantree_print_type'])) {
        $fantree_pdf = true;
      }
      else{
        $fantree_pdf = false;
      }
    }
    else{
      $fantree_png = false;
      $fantree_pdf = false;
    }

      // pedigree print type
    if($request->pedigree_print_type != null){
      if (in_array("png", $inputs['pedigree_print_type'])) {
        $pedigree_png = true;
      }
      else{
        $pedigree_png = false;
      }

      if (in_array("pdf", $inputs['pedigree_print_type'])) {
        $pedigree_pdf = true;
      }
      else{
        $pedigree_pdf = false;
      }
    }
    else{
      $pedigree_png = false;
      $pedigree_pdf = false;
    }

    $image = null;

    if($request->hasFile('image') && $request->file('image')->isValid()) {
      
      // delete image if exist
      if($product->image != null){
        if (Storage::exists($product->image)) {
          Storage::delete($product->image);
        }
      }
      
      // store the new image
      $image = $request->file('image')->store('products');
    }
    
    
    Product::where('id',$id)->update([
      'name' => $inputs['name'],
      'description' => $inputs['description'],
      'image' => $image,
      'fantree' => $fantree,
      'pedigree' => $pedigree,
      'duration' => $inputs['duration'],
      'print_number' => $inputs['print_number'],
      'price' => $inputs['price'],

      'fantree_max_generation' => $inputs['fantree_max_generation'],
      'pedigree_max_generation' => $inputs['pedigree_max_generation'],

      'max_nodes' => $inputs['max_nodes'],

      'fantree_max_output_png' => $inputs['fantree_max_output_png'],
      'fantree_max_output_pdf' => $inputs['fantree_max_output_pdf'],

      'pedigree_max_output_png' => $inputs['pedigree_max_output_png'],
      'pedigree_max_output_pdf' => $inputs['pedigree_max_output_pdf'],


      'fantree_output_png' => $fantree_png,
      'fantree_output_pdf' => $fantree_pdf,

      'pedigree_output_png' => $pedigree_png,
      'pedigree_output_pdf' => $pedigree_pdf,
    ]);

    return redirect()->back()->with('success','Product updated !!');
  }


  public function destroy(Request $request, $id){

    $product = Product::findOrFail($id);

    $product->delete();

    return redirect()->back()->with('success','Product deleted !!');
  }


}
