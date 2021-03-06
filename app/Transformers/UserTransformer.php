<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'identificador' => (int)$user->id,
            'cedula' => (string)$user->ci,
            'nombre' => (string)$user->first_name,
            'apellido' => (string)$user->last_name,
            'esAdministrador' => ($user->admin === 'true'),
            'sexo' => (string)$user->gender,
           /* 'fechaCreacion' => (string)$area->created_at,
            'fechaActualizacion' => (string)$area->updated_at,*/
            'fechaEliminacion' => isset($user->deleted_at) ? (string)$user->deleted_at : null , 
        ];
    }

    public static function originalAttribute($index){
        $attributes = [
            'identificador' => 'id',
            'cedula' => 'ci',
            'nombre' => 'first_name',
            'apellido' => 'last_name',
            'esAdministrador' => 'admin',
            'sexo' => 'gender',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
       
    }

    public static function transformedAttribute($index){
        $attributes = [
            'id' => 'identificador',
            'ci' => 'cedula',
            'first_name' => 'nombre',
            'last_name' => 'apellido',
            'gender' => 'sexo',
            'admin' => 'esAdministrador'
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
       
    }
}
