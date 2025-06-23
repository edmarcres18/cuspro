<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use Illuminate\Http\JsonResponse;

class HospitalApiController extends Controller
{
    /**
     * Display a listing of hospitals.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $hospitals = Hospital::all();
        
        return response()->json([
            'status' => 'success',
            'data' => $hospitals
        ]);
    }
} 