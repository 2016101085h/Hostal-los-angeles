<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rol;

class RolController extends Controller
{
    public function index(Request $request){
        $rol= Rol::orderBy('id','asc')->get();
        return ['rol' => $rol];
    }
    public function getRol(Request $request){
        $rol = Rol::where('estado','=','1')->select('id','nombre')->orderBy('id','asc')->get();
        return ['rol' => $rol];
    }
    public function store(Request $request){
        $rol = new Rol();
        $rol->nombre=$request->nombre;
        $rol->descripcion= $request->descripcion;
        $rol->estado = '1';
        $rol->save();
        return $rol;
    }
    public function update(Request $request){
        $rol=Rol::findOrFail($request->id);
        $rol->nombre=$request->nombre;
        $rol->descripcion= $request->descripcion;
        $rol->estado = '1';
        $rol->save();
        return $rol;
    }
    public function desactivar(Request $request){
        $rol=Rol::findOrFail($request->id);
        $rol->estado = '0';
        $rol->save();
        return $rol;
    }
    public function activar(Request $request){
        $rol=Rol::findOrFail($request->id);
        $rol->estado = '1';
        $rol->save();
        return $rol;
    }
}
