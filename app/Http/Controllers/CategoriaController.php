<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
class CategoriaController extends Controller
{
    public function index(Request $request){
        $categoria= Categoria::orderBy('id','asc')->get();
        return ['categoria' => $categoria];
    }

    public function getCategoria() {
        $categoria=Categoria::select('id','nombre')->get();
        return $categoria;
    }
    public function store(Request $request){
        $categoria = new Categoria();
        $categoria->nombre=$request->nombre;
        $categoria->descripcion= $request->descripcion;
        $categoria->estado = '1';
        $categoria->save();
        return $categoria;
    }
    public function update(Request $request){
        $categoria=Categoria::findOrFail($request->id);
        $categoria->nombre=$request->nombre;
        $categoria->descripcion= $request->descripcion;
        $categoria->estado = '1';
        $categoria->save();
        return $categoria;
    }
    public function desactivar(Request $request){
        $categoria=Categoria::findOrFail($request->id);
        $categoria->estado = '0';
        $categoria->save();
        return $categoria;
    }
    public function activar(Request $request){
        $categoria=Categoria::findOrFail($request->id);
        $categoria->estado = '1';
        $categoria->save();
        return $categoria;
    }
}
