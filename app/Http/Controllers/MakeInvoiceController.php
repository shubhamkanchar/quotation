<?php

namespace App\Http\Controllers;

use App\Models\MakeInvoice;
use Illuminate\Http\Request;

class MakeInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('make-invoice.add');
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
    public function show(MakeInvoice $makeInvoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MakeInvoice $makeInvoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MakeInvoice $makeInvoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MakeInvoice $makeInvoice)
    {
        //
    }
}
