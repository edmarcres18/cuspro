<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Area;
use App\Models\Phss;
use App\Models\Hospital;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $areas = Area::all();
        $hospitals = Hospital::all();
        $phss = Phss::all();
        return view('customers.create',compact('areas', 'phss', 'hospitals'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
        'area_id' => 'required',
        'hospital_id' => 'required',
        'phss_id' => 'required',
        'contact_person' => 'required',
        'position' => 'required',
        'contact_no' => 'required',
        ]);

        $customer = new customer;
        $customer = customer::create([
            'area_id' => $request->area_id,
            'hospital_id' => $request->hospital_id,
            'phss_id' => $request->phss_id,
            'contact_person' => $request->contact_person,
            'position' => $request->position,
            'contact_no' => $request->contact_no,
            
        ]);

        $customer->save();
        return redirect()->route('customers.index')
        ->with('success','Customer created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        $areas = Area::all();
        $phss = Phss::all();
        $hospitals = Hospital::all();
        return view('customers.edit', compact('customer','areas','hospitals','phss'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer): RedirectResponse
{
    $request->validate([
        'area_id' => 'required',
        'hospital_id' => 'required',
        'phss_id' => 'required',
        'contact_person' => 'required',
        'position' => 'required',
        'contact_no' => 'required',
    ]);

    $customer->update($request->all()); // Pass the request data to update the customer

    return redirect()->route('customers.index')
        ->with('success', 'Customer updated successfully');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')
        ->with('success','Customer deleted successfully');
    }
}
