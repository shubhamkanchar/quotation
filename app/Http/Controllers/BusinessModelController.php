<?php

namespace App\Http\Controllers;

use App\DataTables\BusinessDataTable;
use App\Models\BusinessModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusinessModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BusinessDataTable $dataTable)
    {
        $user = auth()->user();
        if($user->business) {
            return redirect()->route('business.edit', $user->business->id);
        }
        return redirect()->route('business.create');
        // return $dataTable->render('business.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('business.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if($request->logo){
            $file = $request->file('logo');
            $logo = $file->store('uploads', 'public');
        }
        if($request->signature){
            $file = $request->file('signature');
            $signature = $file->store('uploads', 'public');
        }
        $business = new BusinessModel();
        $business->business_name = $request->business_name;
        $business->contact_name = $request->contact_name;
        $business->email = $request->email;
        $business->user_id = Auth::user()->id;
        $business->number = $request->number;
        $business->address_1 = $request->address_1;
        $business->address_2 = $request->address_2;
        $business->address_3 = $request->address_3;
        $business->other_info = $request->other_info;
        $business->business_label = $request->business_label;
        $business->business_number = $request->business_number;
        $business->state = $request->state;
        $business->business_category = $request->business_category;
        $business->account_name = $request->account_name;
        $business->account_number = $request->account_number;
        $business->bank_name = $request->bank_name;
        $business->logo = $logo;
        $business->signature = $signature;
        if($business->save()){
            return response()->json([
                'message' => 'Business added successfully'
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
    public function show(BusinessModel $businessModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $businessModel = BusinessModel::whereId($request->business)->first();
        return view('business.edit',compact('businessModel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BusinessModel $businessModel)
    {
        
        $business = BusinessModel::whereId($request->business)->first();
        if($request->logo){
            $file = $request->file('logo');
            $logo = $file->store('uploads', 'public');
            $business->logo = $logo;
        }
        if($request->signature){
            $file = $request->file('signature');
            $signature = $file->store('uploads', 'public');
            $business->signature = $signature;
        }
       
        $business->business_name = $request->business_name;
        $business->contact_name = $request->contact_name;
        $business->email = $request->email;
        $business->user_id = Auth::user()->id;
        $business->number = $request->number;
        $business->address_1 = $request->address_1;
        $business->address_2 = $request->address_2;
        $business->address_3 = $request->address_3;
        $business->other_info = $request->other_info;
        $business->business_label = $request->business_label;
        $business->business_number = $request->business_number;
        $business->state = $request->state;
        $business->business_category = $request->business_category;
        $business->account_name = $request->account_name;
        $business->account_number = $request->account_number;
        $business->bank_name = $request->bank_name;
        
        if($business->save()){
            return response()->json([
                'message' => 'Business updated successfully'
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
        $business = BusinessModel::whereId($request->business)->delete();
        if($business){
            return response()->json([
                'message' => 'Business Deleted successfully'
            ],200);
        }else{
            return response()->json([
                'message' => 'Something went wrong'
            ],400);
        }
    }
}
