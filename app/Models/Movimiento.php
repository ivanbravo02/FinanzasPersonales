<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $fillable = [
        'user_id',
        'categoria_id',
        'tipo',
        'monto',
        'fecha',
        'descripcion',
        'foto',
    ];
    /*relacion de muchos a uno con el modelo user*/
    public function user(){
        return $this->belongsTo(User::class);
    }

    /*relacion de muchos a uno con el modelo categoria*/
    public function categoria(){
        return $this->belongsTo(Categoria::class);
    }
}
