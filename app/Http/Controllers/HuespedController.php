<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Huesped;
class HuespedController extends Controller
{
    public function index(){
        $huesped = Huesped::orderBy('id','asc')->get();
        return ['huesped'=> $huesped];
    }

    public function store(Request $request) {
        $documento = Huesped::where('num_documento', $request->num_documento)->get();
        if (count($documento) > 0) {
            $huesped = 'Existe';
        }else {
            $huesped = new Huesped();
            $huesped->tipo_documento=$request->tipo_documento;
            $huesped->num_documento=$request->num_documento;
            $huesped->nombre=$request->nombre;
            $huesped->fecha_nacimiento=$request->fecha_nacimiento;
            $huesped->celular=$request->celular;
            $huesped->procedencia=$request->procedencia;
            $huesped->save();
        }
        return ['huesped' => $huesped];

    }
    public function update(Request $request){
        $huesped = Huesped::findOrFail($request->id);
        $huesped->tipo_documento=$request->tipo_documento;
        $huesped->num_documento=$request->num_documento;
        $huesped->nombre=$request->nombre;
        $huesped->fecha_nacimiento=$request->fecha_nacimiento;
        $huesped->celular=$request->celular;
        $huesped->procedencia=$request->procedencia;
        $huesped->save();
        return $huesped;
    }
    public function eliminar(Request $request){
        $huesped = Huesped::findOrFail($request->id);
        $huesped->delete();
        return ['mensaje' => 'Eliminado'];

    }
}
