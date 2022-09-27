<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\HealthCare;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class HealthCareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $health_cares= HealthCare::all();
        return view('superAdmin.healthCare.healthCare', compact('health_cares'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('superAdmin.healthCare.create_healthCare');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'bail|required|unique:health_care'
        ]);
        $data = $request->all();
        // dd($request);
         HealthCare::create($data);
         return redirect(route('healthCare.index'));
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
    
        $health_care=HealthCare::find($id);
        return view('superAdmin.healthCare.edit_healthCare',compact('health_care'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'bail|required|unique:health_care'
        ]);

        $health_care = HealthCare::find($id);
        $data = $request->all();
        $health_care->update($data);
        // return redirect()->route('healthCare.show');
        return redirect(route('healthCare.index'))->withStatus(__('Updated Successfully'));
    }
 
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = HealthCare::find($id);
        $id->delete();
        return \response(['success' => true]);
    }
}
