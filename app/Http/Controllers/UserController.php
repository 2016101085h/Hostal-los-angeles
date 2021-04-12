<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, Habitacion, Huesped, TipoHabitacion, Producto, Venta};
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
class UserController extends Controller
{
    public function autenticate(Request $request){
        $credenciales = $request->only('email','password');
        $validator = Validator::make($credenciales, [
            'email' => 'required',
            'password' => 'required'
        ]);
        if(!$validator->fails()){
            try {
               if(! $token = JWTAuth::attempt($credenciales)){
                   return response()->json([
                       'status' => false,
                       'msg' => 'Credenciales invalidas'
                   ]);
               }
            } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
                return response()->json([
                    'status' => false,
                    'error' => $e->getMessage(),
                    'msg' => 'Credenciales invalidas'
                ]);
            }
            return response()->json([
                'status' => true,
                'token' => $token,
                'msg' => 'Credenciales validas',


            ]);
        }else{
            return response()->json([
                'status' => false,
                'error' => $validator->errors()
            ]);
        }
    }
    public function getUserAutenticado(Request $request){
        return response()->json([
            'status'=> true,
            'user' => $request->get('user')
        ]);
    }
    public function getCantidades(){
        $dia_actual = date('Y-m-d');
        // $dato = explode('-', $dia_actual);
        // $dato_m = intval($dato[2]) -1 ;
        // $array_nuevo = array($dato[0], $dato[1], $dato_m);
        // $fecha_formateada = implode('-', $array_nuevo);
        $mes_actual = date('Y-m');
        $Hab_disponibles = Habitacion::where('estado','=','disponible')->get()->count();
        $Hab_ocupadas = Habitacion::where('estado','=','ocupado')->get()->count();
        $Hab_sucias = Habitacion::where('estado','=','sucio')->get()->count();
        $tipo = TipoHabitacion::all()->count();
        $huesped = Huesped::all()->count();
        $venta_dia = Venta::where('fecha_hora', 'like' ,'%'.$dia_actual.'%')->get()->count();
        $venta_mes = Venta::where('fecha_hora', 'like' ,'%'.$mes_actual.'%')->get()->count();
        $productos = Producto::all()->count();
        return [ 'hab_disponibles' => $Hab_disponibles,
                 'hab_ocupadas' => $Hab_ocupadas,
                 'hab_sucias' => $Hab_sucias,
                 'huesped' => $huesped,
                 'tipo_habitacion' => $tipo,
                 'productos' => $productos,
                 'dia_actual' => $venta_dia,
                 'mes_actual' => $venta_mes
                ];
    }

    public function register(Request $request){

    }
    public function index(Request $request){
        $usuario = User::join('rols','users.rol_id','=','rols.id')->select('users.id', 'users.nombre','users.num_documento','users.celular','users.email','users.rol_id','users.imagen','rols.nombre as rol')->orderBy('users.id','asc')->get();
        return ['usuario' => $usuario];
    }
    public function store(Request $request){
        $email = User::where('email','=',$request->email)->get();
        if(count($email) > 0){
            return [ 'msg' => 'Email ya Existe en la DB'];
        }
        $usuario = new User();
        $usuario->nombre = $request->nombre;
        $usuario->num_documento = $request->num_documento;
        $usuario->celular = $request->celular;
        $usuario->rol_id = $request->rol_id;
        $usuario->email = $request->email;
        $usuario->password = bcrypt($request->password);
        if($request->imagen !== null){
            $exploded = explode(',', $request->imagen);
            $decoded = base64_decode($exploded[1]);

            if (Str::contains($exploded[0], 'jpeg')) {

                $extension = 'jpg';
            }else {

                $extension = 'png';
            }

            $fileName = $usuario->nombre. Str::random(2). '.' . $extension;

            $path = public_path() . '/img/usuarios/' . $fileName;

            file_put_contents($path, $decoded);

            $usuario->imagen = $fileName;
        }else{
            $nombreFoto = 'usuario-no-image.jpg';
            $usuario->imagen = $nombreFoto;
        }
        $usuario->save();
        return [ 'usuario' => $usuario  ];

    }
    public function update(Request $request){
        $email = User::where('email','=',$request->email)->get();
        // if(count($email) > 0){
        //     return [ 'msg' => 'Email ya Existe en la DB'];
        // }
        $usuario = User::findOrFail($request->id);
        $usuario->nombre = $request->nombre;
        $usuario->num_documento = $request->num_documento;
        $usuario->celular = $request->celular;
        $usuario->rol_id = $request->rol_id;
        $usuario->email = $request->email;
        if($request->password !== null){

            $usuario->password = bcrypt($request->password);
        }else {
            $usuario->password = $usuario->password ;
        }
        $currentPhoto = $usuario->imagen;
        if($request->imagen !== null){
            if ($request->imagen != $currentPhoto) {

                $exploded = explode(',', $request->imagen);
                $decoded = base64_decode($exploded[1]);

                if (Str::contains($exploded[0], 'jpeg')) {

                    $extension = 'jpg';
                } else {

                    $extension = 'png';
                }

                $fileName = $usuario->nombre . Str::random(2) .'.' . $extension;

                $path = public_path() . '/img/usuarios/' . $fileName;

                file_put_contents($path, $decoded);

                /*inicio eliminar del servidor*/
                $usuarioImagen = public_path('/img/usuarios/') . $currentPhoto;
                if (file_exists($usuarioImagen) && $currentPhoto !== 'usuario-no-image.jpg') {
                    @unlink($usuarioImagen);
                }
                /*fin eliminar del servidor*/
                $usuario->imagen = $fileName;
            }
        }
        $usuario->save();
        return $usuario;

    }
    public function destroy(Request $request){
        $usuario = User::findOrFail($request->id);
        $currentPhoto = $usuario->imagen;
        $usuarioImagen = public_path('/img/usuarios/') . $currentPhoto;
        if (file_exists($usuarioImagen) && $currentPhoto !== 'usuario-no-image.jpg') {
            @unlink($usuarioImagen);
        }
        $usuario->delete();
        return $usuario;
    }
}
