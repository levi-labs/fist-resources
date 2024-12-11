<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;
use App\Services\SupplierService;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    protected $supplierservice;
    public function __construct(SupplierService $supplierservice)
    {
        $this->supplierservice = $supplierservice;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Supplier List';
        $suppliers = $this->supplierservice->getAllSuppliers();
        return view('pages.supplier.index', compact('title', 'suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Add New Supplier';
        return view('pages.supplier.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $this->supplierservice->createSupplier($request->all());
        } catch (\Throwable $error) {
            return redirect()->back()->with('error', $error->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        $title = 'Supplier Details';
        $supplier = $this->supplierservice->getSupplierById($supplier->id);
        return view('pages.supplier.detail', compact('title', 'supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        $title = 'Edit Supplier';
        $supplier = $this->supplierservice->getSupplierById($supplier->id);
        return view('pages.supplier.edit', compact('title', 'supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierRequest $request, Supplier $supplier)
    {
        try {
            $this->supplierservice->updateSupplier($supplier->id, $request->all());
            return redirect()->route('supplier.index')->with('success', 'Supplier updated successfully');
        } catch (\Throwable $error) {
            return redirect()->back()->with('error', $error->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        try {
            $this->supplierservice->deleteSupplier($supplier->id);
            return redirect()->route('supplier.index')->with('success', 'Supplier deleted successfully');
        } catch (\Throwable $error) {
            return redirect()->back()->with('error', $error->getMessage());
        }
    }
}
