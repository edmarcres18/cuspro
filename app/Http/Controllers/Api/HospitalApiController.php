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

    /**
     * Get all hospitals with pagination, sorting, and filtering options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllHospitals(Request $request)
    {
        $query = Hospital::query();

        // Apply search filter if provided
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where('name', 'LIKE', "%{$searchTerm}%");
        }

        // Apply sorting
        $sortField = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        // Pagination
        $perPage = $request->input('per_page', 15);
        $hospitals = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $hospitals->items(),
            'pagination' => [
                'total' => $hospitals->total(),
                'per_page' => $hospitals->perPage(),
                'current_page' => $hospitals->currentPage(),
                'last_page' => $hospitals->lastPage(),
                'from' => $hospitals->firstItem(),
                'to' => $hospitals->lastItem()
            ]
        ]);
    }
}