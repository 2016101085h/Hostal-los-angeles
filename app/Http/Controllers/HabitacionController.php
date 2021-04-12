<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Habitacion;

class HabitacionController extends Controller
{
    public function index(Request $request){
        $habitacion=Habitacion::join('tipo_habitacions','habitacions.tipo_habitacion_id','tipo_habitacions.id')->select('habitacions.id', 'habitacions.numero','habitacions.precio','habitacions.estado', 'habitacions.tipo_habitacion_id','habitacions.piso','tipo_habitacions.nombre')->orderBy('id','asc')->get();
        return ['habitacion' => $habitacion];
    }
    public function selectHabitacion(Request $request){
        $piso = $request->piso;
        $habitacion = Habitacion::join('tipo_habitacions','habitacions.tipo_habitacion_id','tipo_habitacions.id')->select('habitacions.id', 'habitacions.numero','habitacions.precio','habitacions.estado', 'habitacions.tipo_habitacion_id','habitacions.piso','tipo_habitacions.nombre')->where('habitacions.piso','=',$piso)->orderBy('habitacions.id','asc')->get();
        return ['habitacion' => $habitacion];

    }
    public function selectHabitacionOcupada(Request $request){
        $piso = $request->piso;
        $habitacion = Habitacion::join('tipo_habitacions','habitacions.tipo_habitacion_id','tipo_habitacions.id')->select('habitacions.id', 'habitacions.numero','habitacions.precio','habitacions.estado', 'habitacions.tipo_habitacion_id','habitacions.piso','tipo_habitacions.nombre')->where('habitacions.estado','=','ocupado')->where('habitacions.piso','=', $piso)->get();
        return ['habitacion' => $habitacion];
    }
    public function selectHabitacionCheckOut(Request $request){
        $piso = $request->piso;
        $habitacion = Habitacion::join('tipo_habitacions','habitacions.tipo_habitacion_id','tipo_habitacions.id')->select('habitacions.id', 'habitacions.numero','habitacions.precio','habitacions.estado', 'habitacions.tipo_habitacion_id','habitacions.piso','tipo_habitacions.nombre')->where('habitacions.estado','=','checkout')->where('habitacions.piso','=', $piso)->get();
        return ['habitacion' => $habitacion];
    }
    public function getHabitacion(Request $request){
        $id = $request->id;
        $habitacion = Habitacion::join('tipo_habitacions','habitacions.tipo_habitacion_id','tipo_habitacions.id')->select('habitacions.id', 'habitacions.numero','habitacions.precio','habitacions.estado', 'habitacions.tipo_habitacion_id','habitacions.piso','tipo_habitacions.nombre','tipo_habitacions.descripcion')->where('habitacions.id','=',$id)->orderBy('habitacions.id','asc')->get();
        return $habitacion;

    }

    public function store(Request $request){
        $habitacion = new Habitacion();
        $habitacion->numero=$request->numero;
        $habitacion->precio= $request->precio;
        $habitacion->estado = $request->estado;
        $habitacion->piso = $request->piso;
        $habitacion->tipo_habitacion_id=$request->tipo_habitacion_id;
        $habitacion->save();
        return $habitacion;
    }
    public function update(Request $request){
        $habitacion=Habitacion::findOrFail($request->id);
        $habitacion->numero=$request->numero;
        $habitacion->precio= $request->precio;
        $habitacion->estado = $request->estado;
        $habitacion->piso = $request->piso;
        $habitacion->tipo_habitacion_id=$request->tipo_habitacion_id;
        $habitacion->save();
        return $habitacion;
    }
    public function eliminar(Request $request){
        $habitacion=Habitacion::findOrFail($request->id);
        $habitacion->delete();
        return $habitacion;
    }

    public function actualizarEstado(Request $request){
        $habitacion=Habitacion::findOrFail($request->id);
        $habitacion->estado = $request->estado;
        $habitacion->save();
        return $habitacion;
    }

}
