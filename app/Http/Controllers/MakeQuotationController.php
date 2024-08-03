<?php

namespace App\Http\Controllers;

use App\DataTables\QuotationDataTable;
use App\Models\CustomerModel;
use App\Models\MakeQuotation;
use App\Models\ProductModel;
use App\Models\TermsModel;
use Illuminate\Http\Request;

class MakeQuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(QuotationDataTable $dataTable)
    {
        return $dataTable->render('make-quotation.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = CustomerModel::all();
        $products = ProductModel::all();
        $terms = TermsModel::all();
        return view('make-quotation.add',compact('customers','products','terms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(MakeQuotation $makeQuotation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MakeQuotation $makeQuotation)
    {
        return view('make-quotation.edit', compact('makeQuotation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MakeQuotation $makeQuotation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MakeQuotation $makeQuotation)
    {
        //
    }
}
