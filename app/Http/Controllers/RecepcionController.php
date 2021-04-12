<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Habitacion;
use App\Models\Huesped;
use App\Models\Recepcion;
use App\Models\DetalleRecepcion;

class RecepcionController extends Controller
{
  public function guardarRecepcion(Request $request){
    $prueba = Huesped::find($request->huesped_id);
    if($prueba) {
        $recepcion = new Recepcion();
        $recepcion->huesped_id= $request->huesped_id;
        $recepcion->habitacion_id=$request->habitacion_id;
        $recepcion->fecha_entrada = $request->fecha_entrada;
        $recepcion->fecha_salida = $request->fecha_salida;
        $recepcion->medio_pago = $request->medio_pago;
        $recepcion->cant_dias = $request->cant_dias;
        $recepcion->monto = $request->monto;
        $recepcion->save();

        $huesped = 'Existe ctmr';

        $habitacion = Habitacion::findOrFail($request->habitacion_id);
        $habitacion->estado = 'ocupado';
        $habitacion->save();
    }else{
        $huesped = new Huesped();
        $huesped->tipo_documento = $request->tipo_documento;
        $huesped->num_documento = $request->num_documento;
        $huesped->nombre = $request->nombre;
        $huesped->fecha_nacimiento = $request->fecha_nacimiento;
        $huesped->celular = $request->celular;
        $huesped->procedencia = $request->procedencia;
        $huesped->save();

        $recepcion = new Recepcion();
        $recepcion->huesped_id= $huesped->id;
        $recepcion->habitacion_id=$request->habitacion_id;
        $recepcion->fecha_entrada = $request->fecha_entrada;
        $recepcion->fecha_salida = $request->fecha_salida;
        $recepcion->medio_pago = $request->medio_pago;
        $recepcion->monto = $request->monto;
        $recepcion->cant_dias = $request->cant_dias;
        $recepcion->save();

        $habitacion = Habitacion::findOrFail($request->habitacion_id);
        $habitacion->estado = 'ocupado';
        $habitacion->save();

    }
    return [ 'recepcion' => $recepcion,
            'huesped'=> $huesped,
            'habitacion' => $habitacion];

  }
  public function actualizarRecepcion(Request $request){

      $recepcion = Recepcion::findOrFail($request->id);
      $recepcion->monto = $request->monto_actualizado;
      $recepcion->save();
      $detalles = $request->data;

      $habitacion = Habitacion::findOrFail($request->habitacion_id);
      $habitacion->estado = 'checkout';
      $habitacion->save();

      if(count($detalles) > 0) {
          foreach($detalles as $ep => $det) {
            $detalle = new DetalleRecepcion();
            $detalle->recepcion_id = $recepcion->id;
            $detalle->producto_id = $det['producto_id'];
            $detalle->cantidad = $det['cantidad'];
            $detalle->precio = $det['precio'];
            $detalle->estado = 'pendiente';
            $detalle->save();
          }

      }else{
          $detalle = 'no hay data';
      }

      return ['recepcion' => $recepcion,
                'detalle' => $detalle,
                'habitacion' => $habitacion];

  }
  public function getRecepcion(Request $request) {
      $recepcion = Recepcion::join('huespeds','recepcions.huesped_id','huespeds.id')->join('habitacions','recepcions.habitacion_id','habitacions.id')->select('recepcions.id','recepcions.fecha_entrada','recepcions.fecha_salida','recepcions.habitacion_id','recepcions.medio_pago','recepcions.monto', 'recepcions.cant_dias','huespeds.num_documento','huespeds.nombre as nombre_huesped','huespeds.celular','huespeds.procedencia','habitacions.numero as numero_habitacion','habitacions.estado', 'habitacions.precio','habitacions.tipo_habitacion_id', 'tipo_habitacions.nombre as tipo_habitacion')->join('tipo_habitacions','habitacions.tipo_habitacion_id','tipo_habitacions.id')->where('recepcions.habitacion_id','=',$request->id)->orderBy('id','asc')->get();
      return ['recepcion' => $recepcion];
  }

  public function getRecepcionCheckout(Request $request){
      $habitacion_id = $request->habitacion_id;
        $recepcion = Recepcion::join('huespeds','recepcions.huesped_id','huespeds.id')->join('habitacions','recepcions.habitacion_id','habitacions.id')->join('tipo_habitacions', 'habitacions.tipo_habitacion_id','tipo_habitacions.id')->select('recepcions.id','recepcions.fecha_entrada','recepcions.fecha_salida', 'recepcions.cant_dias','recepcions.medio_pago','recepcions.monto', 'habitacions.numero','habitacions.estado','habitacions.precio', 'tipo_habitacions.nombre as tipo_habitacion','tipo_habitacions.imagen', 'huespeds.tipo_documento','huespeds.num_documento','huespeds.nombre as nombre_huesped','huespeds.celular','huespeds.procedencia')->where('recepcions.habitacion_id','=',$habitacion_id)->orderBy('recepcions.fecha_entrada')->orderBy('recepcions.id')->get();

        $recepcion_id = Recepcion::select('id')->where('recepcions.habitacion_id','=', $habitacion_id)->get();
        $numero = count($recepcion_id);
        $detalle = DetalleRecepcion::join('productos','detalle_recepcions.producto_id','productos.id')->join('categorias','productos.categoria_id','categorias.id')->join('recepcions','detalle_recepcions.recepcion_id','recepcions.id')->select('detalle_recepcions.id','productos.codigo','recepcions.habitacion_id','productos.nombre as nombre_producto','detalle_recepcions.cantidad','detalle_recepcions.precio','detalle_recepcions.recepcion_id','categorias.nombre as nombre_categoria')->where('recepcions.habitacion_id','=',$habitacion_id)->where('detalle_recepcions.recepcion_id','=', $recepcion_id[$numero - 1]->id )->where('detalle_recepcions.estado','=','pendiente')->orderBy('detalle_recepcions.id','asc')->get();

        return ['recepcion' => $recepcion,
                'detalle' => $detalle];
  }
  public function prueba(Request $request){
    //   $documento = Huesped::select('num_documento')->get();
      $documento = Huesped::where('num_documento', $request->num_documento)->get();
    //   echo $documento;
      if(count($documento) > 0){
          $dato = 'Existe';
        }else{

            $dato = 'No Existe';
      }
      return['dato' => $dato];
    //   return $documento;

    //   $prueba = Huesped::find($request->id);



  }
}
