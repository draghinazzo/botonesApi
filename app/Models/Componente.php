<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Componente extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'activo',
        'imagen',
    ];
}
