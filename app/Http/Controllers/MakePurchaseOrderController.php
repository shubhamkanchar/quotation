<?php

namespace App\Http\Controllers;

use App\DataTables\PurchaseOrderDataTable;
use App\Models\MakePurchaseOrder;
use Illuminate\Http\Request;

class MakePurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PurchaseOrderDataTable $dataTable)
    {
        return $dataTable->render('make-purchase-order.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('make-purchase-order.add');
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
    public function show(MakePurchaseOrder $makePurchaseOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MakePurchaseOrder $makePurchaseOrder)
    {
        $makePurchaseOrder->load(['otherCharge']);
        return view('make-purchase-order.edit', compact('makePurchaseOrder'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MakePurchaseOrder $makePurchaseOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MakePurchaseOrder $makePurchaseOrder)
    {
        //
    }
}
