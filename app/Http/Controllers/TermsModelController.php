<?php

namespace App\Http\Controllers;

use App\DataTables\TermsDataTable;
use App\Models\TermsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TermsModelController extends Controller
{
    public function index(TermsDataTable $dataTable)
    {
        return $dataTable->render('terms.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('terms.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $terms = new TermsModel();
        $terms->type = $request->type;
        $terms->terms = $request->terms;
        $terms->user_id = Auth::user()->id;
        if($terms->save()){
            return response()->json([
                'message' => 'Terms added successfully'
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
    public function show(TermsModel $TermsModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $TermsModel = TermsModel::whereId($request->term)->first();
        return view('terms.edit',compact('TermsModel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TermsModel $TermsModel)
    {
        $terms = TermsModel::whereId($request->term)->first();
        $terms->type = $request->type;
        $terms->terms = $request->terms;
        $terms->user_id = Auth::user()->id;
        if($terms->save()){
            return response()->json([
                'message' => 'Terms updated successfully'
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
        $terms = TermsModel::whereId($request->term)->delete();
        if($terms){
            return response()->json([
                'message' => 'Terms Deleted successfully'
            ],200);
        }else{
            return response()->json([
                'message' => 'Something went wrong'
            ],400);
        }
    }
}
