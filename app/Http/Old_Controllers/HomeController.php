<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Phss;
use App\Models\Area;
use App\Models\Hospital;
use App\Models\Customer;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get counts of models
        $phssCount = Phss::count();
        $areaCount = Area::count();
        $hospitalCount = Hospital::count();
        $customerCount = Customer::count();
        $userCount = User::count();

        // Pass the counts to the view
        return view('home', compact('phssCount', 'areaCount', 'hospitalCount', 'customerCount', 'userCount'));
    }
}
