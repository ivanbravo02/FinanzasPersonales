<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = [
        'folio', 'user_id', 'cliente_id', 'corte_caja_id',
        'subtotal', 'descuento', 'impuesto', 'total',
        'metodo_pago', 'monto_pagado', 'cambio', 'estado', 'notas',
    ];

    protected static function booted(): void
    {
        static::creating(function (Venta $venta) {
            $venta->folio = 'V-' . str_pad(
                (static::max('id') ?? 0) + 1,
                6, '0', STR_PAD_LEFT
            );
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function corteCaja()
    {
        return $this->belongsTo(CorteCaja::class, 'corte_caja_id');
    }

    public function items()
    {
        return $this->hasMany(VentaItem::class);
    }
}
