<?php

namespace App\Http\Controllers;

use App\DataTables\ProformaInvoiceDataTable;
use App\Models\MakeProformaInvoice;
use Illuminate\Http\Request;

class MakeProformaInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProformaInvoiceDataTable $dataTable)
    {
        return $dataTable->render('make-proforma.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('make-proforma.add');
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
    public function show(MakeProformaInvoice $makeProformaInvoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MakeProformaInvoice $makeProformaInvoice)
    {
        $makeProformaInvoice->load(['otherCharge', 'paidInfos']);
        return view('make-proforma.edit', compact('makeProformaInvoice'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MakeProformaInvoice $makeProformaInvoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MakeProformaInvoice $makeProformaInvoice)
    {
        $user = auth()->user();
        if($makeProformaInvoice->created_by != $user->id ) {
            return abort(403, 'Unauthorized');
        }
        try {
            $makeProformaInvoice->delete();
            return response()->json(['message' => 'Proforma Invoice Deleted Sucessfully'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Something went wrong'], 400);
        }
    }
}
