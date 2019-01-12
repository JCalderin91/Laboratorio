<?php

namespace App\Http\Controllers;

use App\SubDevice;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Transformers\SubDeviceTransformer;

class SubDeviceController extends ApiController
{
    public function __construct(){
        //parent::__construct();

        $this->middleware('transform.input:' . SubDeviceTransformer::class)->only(['store', 'update']);
    }
    
    
    public function index()
    {
        $subDevices = SubDevice::orderBy('name', 'asc')->get();

        return $this->showAll($subDevices);
    }

   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $subDevices = SubDevice::create($request->all());

        if(request()->has('paginate')){

            return $this->showAll($subDevice, 200, true);
        }

        return $this->showOne($subDevice, 201);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubDevice $subDevice)
    {
        
        if($request->has('name')){
            $subDevice->name = $request->name;
        }

        if(!$subDevice->isDirty()){
            return $this->errorResponse('Se debe especificar  un valor diferente para actualizar' , 422);        
        }

        $subDevice->save();

        return $this->showOne($subDevice);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubDevice $subDevice)
    {     

        $subDevice->delete();

        return $this->showOne($subDevice);
    
    }
}
