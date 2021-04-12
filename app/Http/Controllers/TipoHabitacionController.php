<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoHabitacion;
use Illuminate\Support\Str;

class TipoHabitacionController extends Controller
{
    public function index(Request $request){
        $tipo=TipoHabitacion::orderBy('id','asc')->get();
        $cantidad = TipoHabitacion::all()->count();
        return ['tipo' => $tipo,
                'cantidad' => $cantidad];
    }
    public function getTipoHabitacion(Request $request){
        $tipo = TipoHabitacion::select('id','nombre')->orderBy('id','asc')->get();
        return ['tipo' => $tipo];

    }

    public function store(Request $request){
        $tipo = new TipoHabitacion();
        $tipo->nombre=$request->nombre;
        $tipo->descripcion= $request->descripcion;
        $exploded = explode(',', $request->imagen);
        $decoded = base64_decode($exploded[1]);

        if (Str::contains($exploded[0], 'jpeg')) {

            $extension = 'jpg';
        } else {

            $extension = 'png';
        }

        $fileName = $tipo->nombre. Str::random(2). '.' . $extension;

        $path = public_path() . '/img/TipoHabitacion/' . $fileName;

        file_put_contents($path, $decoded);

        $tipo->imagen = $fileName;
        $tipo->save();
        return $tipo;
    }
    public function update(Request $request){
        $tipo=TipoHabitacion::findOrFail($request->id);
        $tipo->nombre=$request->nombre;
        $tipo->descripcion= $request->descripcion;
        $currentPhoto = $tipo->imagen;
        if($request->imagen !== null){
        if ($request->imagen != $currentPhoto) {

            $exploded = explode(',', $request->imagen);
            $decoded = base64_decode($exploded[1]);

            if (Str::contains($exploded[0], 'jpeg')) {

                $extension = 'jpg';
            } else {

                $extension = 'png';
            }

            $fileName = $tipo->nombre . Str::random(2) .'.' . $extension;

            $path = public_path() . '/img/TipoHabitacion/' . $fileName;

            file_put_contents($path, $decoded);

            /*inicio eliminar del servidor*/
            $usuarioImagen = public_path('/img/TipoHabitacion/') . $currentPhoto;
            if (file_exists($usuarioImagen)) {
                @unlink($usuarioImagen);
            }
            /*fin eliminar del servidor*/
            $tipo->imagen = $fileName;
        }
    }
        $tipo->save();
        return $tipo;
    }
    public function eliminar(Request $request){
        $tipo=TipoHabitacion::findOrFail($request->id);
        $currentPhoto = $tipo->imagen;
        $usuarioImagen = public_path('/img/TipoHabitacion/') . $currentPhoto;
        if (file_exists($usuarioImagen)) {
            @unlink($usuarioImagen);
        }

        $tipo->delete();
        return $tipo;
    }

}
