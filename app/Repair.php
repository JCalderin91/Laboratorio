<?php

namespace App;

use Carbon\Carbon;
use App\Transformers\RepairTransformer;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    
    const ORDER_REPAIRED = 'repaired';
    const ORDER_WITHOUT_REPAIR = 'without repair';


    public $transformer = RepairTransformer::class;

    protected $dates = ['created'];
    
    protected $fillable = [
        'user_id', 
        'status', 
        'description', 
    ];

    public $timestamps = false;

    public function order(){
        return $this->belongsTo('App\Order')->withTrashed();
    }

    public function user(){
        return $this->belongsTo('App\User')->withTrashed();
    }

    public function isRepair(){
        return $this->status == Repair::ORDER_REPAIRED;
    }

    public function getCreatedAttribute($value){
        $dateTime = Carbon::parse($value);
        return $dateTime->format('d-m-Y H:i:s');
    }

}
