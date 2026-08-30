<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CorteCaja extends Model
{
    protected $table = 'cortes_caja';

    protected $fillable = [
        'user_id', 'fondo_inicial', 'efectivo_contado',
        'total_ventas', 'total_efectivo', 'total_tarjeta', 'total_transferencia',
        'diferencia', 'notas', 'estado', 'abierto_en', 'cerrado_en',
    ];

    protected $casts = [
        'abierto_en' => 'datetime',
        'cerrado_en' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function estaAbierto(): bool
    {
        return $this->estado === 'abierto';
    }

    public function calcularTotales(): void
    {
        $ventas = $this->ventas()->where('estado', 'completada');
        $this->total_ventas = $ventas->sum('total');
        $this->total_efectivo = $ventas->where('metodo_pago', 'efectivo')->sum('total');
        $this->total_tarjeta = $ventas->where('metodo_pago', 'tarjeta')->sum('total');
        $this->total_transferencia = $ventas->where('metodo_pago', 'transferencia')->sum('total');
    }
}
