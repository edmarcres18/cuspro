<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Phss;
use App\Models\Area;

class PhssApiController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $phss = Phss::with('area')->get();
        return response()->json([
            'status' => 'success',
            'data' => $phss
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
            'full_name' => 'required|string|max:255',
            'area_id' => 'required|exists:areas,id'
        ]);

        $phss = Phss::create($request->all());
        
        return response()->json([
            'status' => 'success',
            'message' => 'PHSS created successfully',
            'data' => $phss
        ], 201);
    }

    /**
     * Display the specified resource.
     * 
     * @param  \App\Models\Phss  $phss
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Phss $phss)
    {
        $phss->load('area');
        return response()->json([
            'status' => 'success',
            'data' => $phss
        ]);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Phss  $phss
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Phss $phss)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'area_id' => 'required|exists:areas,id'
        ]);

        $phss->update($request->all());
        
        return response()->json([
            'status' => 'success',
            'message' => 'PHSS updated successfully',
            'data' => $phss
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param  \App\Models\Phss  $phss
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Phss $phss)
    {
        $phss->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'PHSS deleted successfully'
        ]);
    }
} 