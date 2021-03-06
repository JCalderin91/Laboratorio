<?php

namespace App\Transformers;

use App\Order;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'tecnico',
        'cliente',
        'equipo',
        'reparacion'
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform (Order $order)
    {
        return [
            'identificador' => (int)$order->id,
            'codigo' => (string)$order->serial,
            'estado' => (string)$order->status,
            'detalle' => (string)$order->description,
            'fechaCreacion' => (string)$order->arrival_date,
            'fechaEntrega' => isset($order->delivery_date) ? (string)$order->delivery_date : null,
            'tecnicoEntrega' => isset($order->user_delivery_id) ? (int)$order->user_delivery_id : null,
            'cedulaEntrega' => isset($order->client_ci) ? (string)$order->dclient_ci : null,
            'nombreEntrega' => isset($order->client_name) ? (string)$order->client_name : null,

            // 'fechaEliminacion' => isset($order->deleted_at) ? (string)$order->deleted_at : null ,
        ];
    }

    public static function originalAttribute($index){
        $attributes = [
            'identificador' => 'id',
            'codigo' => 'serial',
            'estado' => 'status',
            'detalle' => 'description',
            'fechaCreacion' => 'arrival_date',
            'equipo' => 'device_id',
            'nombre_equipo' => 'name',
            'marca' => 'title',
            'modelo' => 'model',  	  	
            'bienNacional' => 'b_n',
            'cedula' => 'ci',
            'nombres' => 'first_name',
            'apellidos' => 'last_name',
            'telefono' => 'phone',
            'area' =>  'area_id',
            'direccion' => 'address_id',
            'tecnico' => 'user_id',
            'fechaEntrega' => 'delivery_date',
            'tecnicoEntrega' => 'user_delivery_id',
            'cedulaEntrega' => 'client_ci',
            'nombreEntrega' => 'client_name',

        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
       
    }

    public static function transformedAttribute($index){
        $attributes = [
            'id' => 'identificador',
            'serial' => 'codigo',
            'status' => 'estado',
            'description' => 'detalle',
            'arrival_date' => 'fechaCreacion',
            'device_id' => 'equipo',
            'name' => 'nombre_equipo',
            'title' => 'marca',
            'model' => 'modelo',
            'b_n' => 'bienNacional',
            'ci' => 'cedula',
            'first_name' => 'nombres',
            'last_name' => 'apellidos',
            'phone' => 'telefono',
            'area_id' => 'area',
            'address_id' => 'direccion',
            'user_id' => 'tecnico',
            'delivery_date' => 'fechaEntrega',
            'user_delivery_id' => 'tecnicoEntrega',
            'client_ci' => 'cedulaEntrega',
            'client_name' => 'nombreEntrega',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
       
    }

    public function includeTecnico(Order $order){
        
        $user = $order->user;

        return $this->item($user, new UserTransformer);
    }

    public function includeCliente(Order $order){
        
        $client = $order->client;

        return $this->item($client, new ClientTransformer);
    }

    public function includeEquipo(Order $order){
        
        $device = $order->device;

        return $this->item($device, new DeviceTransformer);
    }

    public function includeReparacion(Order $order){
        
        $device = $order->repair;
        
        if(!$device){
            return null;
        }else{
            return $this->item($device, new RepairTransformer);
        }
    }
}
