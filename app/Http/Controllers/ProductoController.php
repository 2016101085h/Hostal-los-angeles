<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
class ProductoController extends Controller
{
    public function index(Request $request){
        $producto=Producto::join('categorias','productos.categoria_id','categorias.id')->select('productos.id', 'productos.nombre','productos.categoria_id','productos.precio_venta','productos.stock', 'productos.codigo','categorias.nombre as categoria')->orderBy('productos.id','asc')->get();
        return ['producto' => $producto];
    }

    public function listarProducto(Request $request) {
        $producto = Producto::join('categorias','productos.categoria_id','categorias.id')->select('productos.id','productos.codigo','productos.nombre as nombre_producto','productos.precio_venta','productos.stock','categorias.nombre as nombre_categoria')->orderBy('productos.id','asc')->get();
        return ['productos'=> $producto];
    }


    public function store(Request $request){
        $producto = new Producto();
        $producto->categoria_id=$request->categoria_id;
        $producto->nombre= $request->nombre;
        $producto->codigo = $request->codigo;
        $producto->precio_venta = $request->precio_venta;
        $producto->stock=$request->stock;
        $producto->save();
        return $producto;
    }
    public function update(Request $request){
        $producto=Producto::findOrFail($request->id);
        $producto->categoria_id=$request->categoria_id;
        $producto->nombre= $request->nombre;
        $producto->codigo = $request->codigo;
        $producto->precio_venta = $request->precio_venta;
        $producto->stock=$request->stock;
        $producto->save();
        return $producto;
    }
    public function eliminar(Request $request){
        $producto=Producto::findOrFail($request->id);
        $producto->delete();
        return $producto;
    }


}
