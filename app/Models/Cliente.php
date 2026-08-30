<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = ['nombre', 'telefono', 'email', 'rfc', 'activo'];

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
}
