<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;

class CustomerApiController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $customers = Customer::with(['area', 'hospital', 'phss'])->get();
        return response()->json([
            'status' => 'success',
            'data' => $customers
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'area_id' => 'required|exists:areas,id',
            'hospital_id' => 'required|exists:hospitals,id',
            'phss_id' => 'required|exists:phsses,id',
            'contact_person' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'contact_no' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $customer = Customer::create($request->all());
        
        return response()->json([
            'status' => 'success',
            'message' => 'Customer created successfully',
            'data' => $customer
        ], 201);
    }

    /**
     * Display the specified resource.
     * 
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Customer $customer)
    {
        $customer->load(['area', 'hospital', 'phss']);
        return response()->json([
            'status' => 'success',
            'data' => $customer
        ]);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Customer $customer)
    {
        $validator = Validator::make($request->all(), [
            'area_id' => 'required|exists:areas,id',
            'hospital_id' => 'required|exists:hospitals,id',
            'phss_id' => 'required|exists:phsses,id',
            'contact_person' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'contact_no' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $customer->update($request->all());
        
        return response()->json([
            'status' => 'success',
            'message' => 'Customer updated successfully',
            'data' => $customer
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Customer deleted successfully'
        ]);
    }
} 