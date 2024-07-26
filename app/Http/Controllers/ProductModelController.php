<?php

namespace App\Http\Controllers;

use App\DataTables\ProductDataTable;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductModelController extends Controller
{
    public function index(ProductDataTable $dataTable)
    {
        return $dataTable->render('product.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = new ProductModel();
        $product->product_name = $request->product_name;
        $product->price = $request->price;
        $product->unit = $request->unit;
        $product->user_id = Auth::user()->id;
        $product->tax = $request->tax;
        $product->description = $request->description;
        $product->hsn = $request->hsn;
        if($product->save()){
            return response()->json([
                'message' => 'Product added successfully'
            ],200);
        }else{
            return response()->json([
                'message' => 'Something went wrong'
            ],400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductModel $ProductModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $ProductModel = ProductModel::whereId($request->product)->first();
        return view('product.edit',compact('ProductModel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,ProductModel $ProductModel)
    {
        $product = ProductModel::whereId($request->product)->first();
        $product->product_name = $request->product_name;
        $product->price = $request->price;
        $product->unit = $request->unit;
        $product->user_id = Auth::user()->id;
        $product->tax = $request->tax;
        $product->description = $request->description;
        $product->hsn = $request->hsn;
        if($product->save()){
            return response()->json([
                'message' => 'Product updated successfully'
            ],200);
        }else{
            return response()->json([
                'message' => 'Something went wrong'
            ],400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $product = ProductModel::whereId($request->product)->delete();
        if($product){
            return response()->json([
                'message' => 'Product Deleted successfully'
            ],200);
        }else{
            return response()->json([
                'message' => 'Something went wrong'
            ],400);
        }
    }
}
