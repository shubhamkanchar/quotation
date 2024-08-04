<?php

namespace App\Http\Controllers;

use App\DataTables\DeliveryNoteDataTable;
use App\Models\MakeDeliveryNote;
use Illuminate\Http\Request;

class MakeDeliveryNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(DeliveryNoteDataTable $dataTable)
    {
        return $dataTable->render('make-delivery-notes.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('make-delivery-notes.add');
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
    public function show(MakeDeliveryNote $makeDeliveryNote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MakeDeliveryNote $makeDeliveryNote)
    {
        return view('make-delivery-notes.edit', compact('makeDeliveryNote'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MakeDeliveryNote $makeDeliveryNote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MakeDeliveryNote $makeDeliveryNote)
    {
        //
    }
}
