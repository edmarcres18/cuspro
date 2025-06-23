<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Phss;
use App\Models\Area;

class PhssController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $phss = Phss::all();
        return view('phss.index', compact('phss'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $areas = Area::all();
        return view('phss.create', compact('areas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Phss::create($request->all());
        return redirect()->route('phss.index')
        ->with('success','PHSS created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Phss $phss)
    {
        return view('phss.show', compact('phss'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Phss $phss)
    {
        $areas = Area::all();
        return view('phss.edit', compact('phss', 'areas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Phss $phss)
    {
        $phss->update($request->all());
        return redirect()->route('phss.index')
        ->with('success','PHSS updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Phss $phss)
    {
        $phss->delete();
        return redirect()->route('phss.index')
        ->with('success','PHSS deleted successfully');
    }
}
