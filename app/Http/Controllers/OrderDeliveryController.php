<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use App\Transformers\OrderTransformer;
use App\Http\Controllers\ApiController;
Use Carbon\Carbon;

class OrderDeliveryController extends ApiController
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
        //
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Order $order, Request $request)
    {

        $order->fill($request->except('delivery_date'));

        if($request->has('delivery_date') && !empty($request->delivery_date)){

            $dateTime = Carbon::parse($request->delivery_date);
            $order->delivery_date = $dateTime->format('Y-m-d H:i:s');
        }else{

            $order->delivery_date = Carbon::now();
        }

        $order->status = Order::ORDER_DELIVERED;
        $order->save();

        return $this->showOne($order, 201);
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
    public function update(Request $request, Order $order)
    {
        $order->fill($request->except(['delivery_date', 'serial']));

        if($request->has('delivery_date') && !empty($request->delivery_date)){

            $dateTime = Carbon::parse($request->delivery_date);
            $order->delivery_date = $dateTime->format('Y-m-d H:i:s');
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
        $order->delivery_date = null;
        $order->user_delivery_id = null;
        $order->client_ci = null;
        $order->client_name = null;
        $order->status = Order::ORDER_REVISED;
        $order->save();

        return $this->showOne($order);
    }
}
