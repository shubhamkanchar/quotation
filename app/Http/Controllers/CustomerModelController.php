<?php

namespace App\Http\Controllers;

use App\DataTables\CustomersDataTable;
use App\Models\CustomerModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CustomersDataTable $dataTable)
    {
        return $dataTable->render('customer.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customer.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $customer = new CustomerModel();
        $customer->name = $request->name;
        $customer->company_name = $request->company_name;
        $customer->email = $request->email;
        $customer->user_id = Auth::user()->id;
        $customer->number = $request->full_number;
        $customer->address_1 = $request->address_1;
        $customer->address_2 = $request->address_2;
        $customer->other_info = $request->other_info;
        $customer->gstin = $request->gstin_number;
        $customer->country = $request->country;
        $customer->state = $request->state;
        $customer->shipping_address = $request->shipping_address;
        if($customer->save()){
            return response()->json([
                'message' => 'Customer added successfully',
                'route' => route('customer.index')
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
    public function show(CustomerModel $CustomerModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $CustomerModel = CustomerModel::whereId($request->customer)->first();
        return view('customer.edit',compact('CustomerModel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomerModel $CustomerModel)
    {
        $customer = CustomerModel::whereId($request->customer)->first();
        $customer->name = $request->name;
        $customer->company_name = $request->company_name;
        $customer->email = $request->email;
        $customer->user_id = Auth::user()->id;
        $customer->number = $request->full_number;
        $customer->address_1 = $request->address_1;
        $customer->address_2 = $request->address_2;
        $customer->other_info = $request->other_info;
        $customer->gstin = $request->gstin_number;
        $customer->state = $request->state;
        $customer->country = $request->country;
        $customer->shipping_address = $request->shipping_address;
        if($customer->save()){
            return response()->json([
                'message' => 'Customer updated successfully'
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
        $customer = CustomerModel::whereId($request->customer)->delete();
        if($customer){
            return response()->json([
                'message' => 'Customer Deleted successfully'
            ],200);
        }else{
            return response()->json([
                'message' => 'Something went wrong'
            ],400);
        }
    }
}
