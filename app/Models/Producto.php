<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'categoria_id', 'nombre', 'codigo', 'descripcion',
        'precio_compra', 'precio_venta', 'stock', 'stock_minimo', 'activo',
    ];

    protected $casts = ['activo' => 'boolean'];

    public function categoria()
    {
        return $this->belongsTo(CategoriaProducto::class, 'categoria_id');
    }

    public function ventaItems()
    {
        return $this->hasMany(VentaItem::class);
    }

    public function stockBajo(): bool
    {
        return $this->stock <= $this->stock_minimo;
    }
}
