<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recepcion extends Model
{
    use HasFactory;
    protected $fillable = ['huesped_id','habitacion_id','fecha_entrada','fecha_salida','monto','medio_pago','cant_dias'];
}
