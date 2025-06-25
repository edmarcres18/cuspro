<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hospital;

class HospitalApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $hospitals = Hospital::all();
        return response()->json([
            'status' => 'success',
            'data' => $hospitals
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
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $hospital = Hospital::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Hospital created successfully',
            'data' => $hospital
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Hospital  $hospital
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Hospital $hospital)
    {
        return response()->json([
            'status' => 'success',
            'data' => $hospital
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hospital  $hospital
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Hospital $hospital)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $hospital->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Hospital updated successfully',
            'data' => $hospital
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Hospital  $hospital
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Hospital $hospital)
    {
        $hospital->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Hospital deleted successfully'
        ]);
    }
}
