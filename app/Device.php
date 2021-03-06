<?php

namespace App;

use App\Transformers\DeviceTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public $transformer = DeviceTransformer::class;
    
    protected $fillable = [
        'sub_device_id', 
        'brand_id', 
        'client_id', 
        'b_n', 
        'model',
    ];

    public function orders(){
        return $this->hasMany('App\Order')->withTrashed();
    }

    public function brand(){
        return $this->belongsTo('App\Brand')->withTrashed();
    }

    public function client(){
        return $this->belongsTo('App\Client')->withTrashed();
    }

    public function subDevice(){
        return $this->belongsTo('App\SubDevice')->withTrashed();
    }


}
