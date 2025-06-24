<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Phss;
use App\Models\Area;
use App\Models\Hospital;
use App\Models\Customer;
use App\Models\User;

class HomeApiController extends Controller
{
    /**
     * Get dashboard statistics.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Get counts of models
        $phssCount = Phss::count();
        $areaCount = Area::count();
        $hospitalCount = Hospital::count();
        $customerCount = Customer::count();
        $userCount = User::count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'phss_count' => $phssCount,
                'area_count' => $areaCount,
                'hospital_count' => $hospitalCount,
                'customer_count' => $customerCount,
                'user_count' => $userCount
            ]
        ]);
    }
} 