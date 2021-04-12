<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleRecepcion extends Model
{
    use HasFactory;
    protected $fillable = ['recepcion_id','articulo_id','cantidad','precio','estado'];
}
