<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Area;

class AreaApiController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $areas = Area::all();
        return response()->json([
            'status' => 'success',
            'data' => $areas
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

        $area = Area::create($request->all());
        
        return response()->json([
            'status' => 'success',
            'message' => 'Area created successfully',
            'data' => $area
        ], 201);
    }

    /**
     * Display the specified resource.
     * 
     * @param  \App\Models\Area  $area
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Area $area)
    {
        return response()->json([
            'status' => 'success',
            'data' => $area
        ]);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Area  $area
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Area $area)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $area->update($request->all());
        
        return response()->json([
            'status' => 'success',
            'message' => 'Area updated successfully',
            'data' => $area
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param  \App\Models\Area  $area
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Area $area)
    {
        $area->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Area deleted successfully'
        ]);
    }
} 