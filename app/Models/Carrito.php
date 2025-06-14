<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    protected $table = 'carritos';

    protected $fillable = ['usuario_id', 'completado'];

    // Relación con productos
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'carrito_productos')
            ->withPivot('cantidad')
            ->withTimestamps();
    }

    // (Opcional) Relación con usuario, si quieres
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
