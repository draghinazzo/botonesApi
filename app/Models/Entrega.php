<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    /**
     * Los campos que se pueden asignar masivamente.
     *
     * @var array
     */
    protected $fillable = [
        'nombre_entrega',
        'nombre_negocio',
        'fecha',
        'tipo_boton',
        'observaciones',
        'componentes',
    ];
}
