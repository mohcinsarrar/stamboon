<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\DataTables\ProductsDataTable;
use App\Models\Product;

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
      'amount' => 'required|integer|min:0',
      'description' => 'required|string',
      'features' => 'required|array|min:1',
      'features.*' => 'string',
    ])->validate();

    
    Product::create($inputs);

    return redirect()->back()->with('success','Product created !!');
  }


  public function update(Request $request, $id){

    $product = Product::findOrFail($id);

    $inputs = $request->except(['_token','_method']);

    Validator::make($inputs, [
      'name' => 'required|string|unique:products,name,'.$id,
      'amount' => 'required|integer|min:0',
      'description' => 'required|string',
      'features' => 'required|array|min:1',
      'features.*' => 'string',
    ])->validate();

    
    Product::where('id',$id)->update($inputs);

    return redirect()->back()->with('success','Product updated !!');
  }


  public function destroy(Request $request, $id){

    $product = Product::findOrFail($id);

    $product->delete();

    return redirect()->back()->with('success','Product deleted !!');
  }


}
