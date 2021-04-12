<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;
    protected $fillabel = ['fecha_hora','total','estado','recepcion_id','medio_pago','comprobante'];
}

