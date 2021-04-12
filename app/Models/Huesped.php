<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Huesped extends Model
{
    use HasFactory;
    protected $fillable = ['tipo_documento','num_documento','nombre','fecha_nacimiento','celular','procedencia'];
    // $table->string('tipo_documento');
    //         $table->string('documento')->unique();
    //         $table->string('nombre');
    //         $table->date('fecha_nacimiento');
    //         $table->string('celular');
    //         $table->string('procedencia');
    //         $table->timestamps();
}
