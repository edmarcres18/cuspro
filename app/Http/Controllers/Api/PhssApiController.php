<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Phss;
use Illuminate\Http\JsonResponse;

class PhssApiController extends Controller
{
    /**
     * Display a listing of PHSS.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $phss = Phss::all();
        
        return response()->json([
            'status' => 'success',
            'data' => $phss
        ]);
    }
} 