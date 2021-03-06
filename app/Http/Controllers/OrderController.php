<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Order;
use App\Client;
use App\Device;
use App\SubDevice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Transformers\OrderTransformer;
use App\Http\Controllers\ApiController;
use App\Http\Requests\OrderStoreRequest;

class OrderController extends ApiController
{
   
    public function __construct(){
        
        //parent::__construct();

        $this->middleware('transform.input:' . OrderTransformer::class)->only(['store', 'update']);
    }
   
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::orderBy('arrival_date', 'asc')->where('status','<>','entregado')->get();
  
        if(request()->has('paginate')){

            return $this->showAll($orders, 200, true);
        }
        
        return $this->showAll($orders);
       
    }

    public function all()
    {
        $orders = Order::orderBy('arrival_date', 'asc')->get();
  
        if(request()->has('paginate')){

            return $this->showAll($orders, 200, true);
        }
        
        return $this->showAll($orders);
       
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderStoreRequest $request)
    {
        DB::beginTransaction();
        
        try {
            
            $client = Client::firstOrCreate(['ci' => $request->ci],
            [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'area_id' => $request->area_id,
                'address_id' => $request->address_id,
                'phone' => $request->phone
            ]);
    
            $sub_device = SubDevice::withTrashed()->firstOrCreate(['name' => strtolower($request->name)]);
    
            $brand = Brand::withTrashed()->firstOrCreate(['title' => strtolower($request->title)]); 
    
            $device = Device::firstOrCreate(['id' => $request->device_id],
            [
                'client_id' => $client->id,
                'sub_device_id' => $sub_device->id,
                'brand_id' => $brand->id,
                'model' => $request->model, 
                'b_n' => $request->b_n, 
            ]);
    
            $order = new Order;
    
            $order->fill([
                'client_id' => $client->id,
                'device_id' => $device->id,
                'user_id' => $request->user_id,
                'serial' => Order::getSerial(),
                'description' => $request->description, 
                'status' => Order::ORDER_PENDING, 
            ]);
    
            if($request->has('arrival_date') && !empty($request->arrival_date)){
    
                $dateTime = Carbon::parse($request->arrival_date);
                $order->arrival_date = $dateTime->format('Y-m-d H:i:s');
            }else{
                $order->arrival_date = Carbon::now();
            }
    
            $order->save();

            DB::commit();
    
            return $this->showOne($order, 201); 
        
        }catch(\Exception $e){

            DB::rollback();
            //return $e->getMessage();
            return $this->errorResponse('Ha ocurrido un error. Intente de nuevo.', 409);
        }
        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return $this->showOne($order);
    }

   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
       $order->fill($request->except(['arrival_date', 'serial']));

       if($request->has('arrival_date') && !empty($request->arrival_date)){

            $dateTime = Carbon::parse($request->arrival_date);
            $order->arrival_date = $dateTime->format('Y-m-d H:i:s');
        }

        $order->save();

        return $this->showOne($order);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();
        
        return $this->showOne($order);
    }
}
