<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\Models\Venta;
use App\Models\Habitacion;
use Barryvdh\DomPDF\PDF ;
use App\Models\DetalleRecepcion;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class VentaController extends Controller
{
    public function index(Request $request) {
        $buscar = $request->buscar;
        $venta = Venta::where('fecha_hora','like','%'.$buscar.'%')->get();
        return ['venta' => $venta];
    }
    public function ventasPDF($buscar) {
        $suma = 0;
        $venta = Venta::where('fecha_hora','like','%'.$buscar.'%')->get();
        for ($i = 0; $i < count($venta); $i++){
            $suma = $suma + floatval($venta[$i]->total);
        }
        $pdf = \PDF::loadView('pdf.ventas', ['ventas'=>$venta, 'total' => $suma]);
        $pdf->setPaper('a7', 'portrait');
        return $pdf->stream('Venta-'.'.pdf');
    }
    public function generarVenta(Request $request) {
        $mytime = Carbon::now('America/Lima');
        $venta = new Venta();
        $venta->fecha_hora = $mytime->toDateTimeString();
        $venta->total = $request->monto_total;
        $venta->comprobante = $request->comprobante;
        $venta->medio_pago = $request->medio_pago;
        $venta->estado = 'registrado';
        $venta->recepcion_id = $request->recepcion_id;
        $venta->save();
        // $this->listarPdf($venta->recepcion_id, $venta->id);

        $habitacion = Habitacion::findOrFail($request->habitacion_id);
        $habitacion->estado = 'sucio';
        $habitacion->save();

        return ['venta' => $venta,
                'habitacion' => $habitacion];

    }

    public function anular(Request $request) {
        $venta = Venta::findOrFail($request->id);
        $venta->estado = 'Anulado';
        $venta->save();
        return ['venta' => $venta];
    }

    public function getData(Request $request) {
        $venta = Venta::join('recepcions','ventas.recepcion_id', 'recepcions.id')->join('huespeds','recepcions.huesped_id','huespeds.id')->join('habitacions','recepcions.habitacion_id','habitacions.id')->join('tipo_habitacions','habitacions.tipo_habitacion_id','tipo_habitacions.id')->select('ventas.id','ventas.recepcion_id','recepcions.fecha_entrada','recepcions.fecha_salida', 'recepcions.cant_dias','recepcions.monto','huespeds.nombre as nombre_huesped','huespeds.num_documento','huespeds.celular','huespeds.procedencia','habitacions.numero','habitacions.precio','habitacions.estado','tipo_habitacions.nombre as tipo','tipo_habitacions.imagen')->orderBy('ventas.id','asc')->where('ventas.recepcion_id','=', $request->recepcion_id)->where('ventas.id','=',$request->id)->get();

        $detalle = DetalleRecepcion::join('productos','detalle_recepcions.producto_id','productos.id')->join('categorias','productos.categoria_id','categorias.id')->select('detalle_recepcions.id','detalle_recepcions.cantidad', 'detalle_recepcions.estado','detalle_recepcions.precio','productos.nombre as producto', 'productos.codigo', 'categorias.nombre as categoria')->where('recepcion_id','=',$request->recepcion_id)->get();
        return ['venta' => $venta,
                'detalle' => $detalle];




    }
    public function listarPdf($recepcion_id, $venta_id) {
        $venta = Venta::join('recepcions','ventas.recepcion_id', 'recepcions.id')->join('huespeds','recepcions.huesped_id','huespeds.id')->join('habitacions','recepcions.habitacion_id','habitacions.id')->join('tipo_habitacions','habitacions.tipo_habitacion_id','tipo_habitacions.id')->select('ventas.id','ventas.recepcion_id','recepcions.fecha_entrada','recepcions.fecha_salida', 'recepcions.cant_dias','recepcions.monto','huespeds.nombre as nombre_huesped','huespeds.num_documento','huespeds.celular','huespeds.procedencia','habitacions.numero','habitacions.precio','habitacions.estado','tipo_habitacions.nombre as tipo','tipo_habitacions.imagen')->orderBy('ventas.id','asc')->where('ventas.recepcion_id','=', $recepcion_id)->where('ventas.id','=',$venta_id)->get();

        $detalle = DetalleRecepcion::join('productos','detalle_recepcions.producto_id','productos.id')->join('categorias','productos.categoria_id','categorias.id')->select('detalle_recepcions.id','detalle_recepcions.cantidad', 'detalle_recepcions.estado','detalle_recepcions.precio','productos.nombre as producto', 'productos.codigo', 'categorias.nombre as categoria')->where('recepcion_id','=',$recepcion_id)->get();
        $pdf = \PDF::loadView('pdf.recepcion', ['ventas'=>$venta,'detalles'=>$detalle]);
        $pdf->setPaper('a7', 'portrait');
        return $pdf->stream('Venta-'.'.pdf');
    }
    public function impresora($recepcion_id, $venta_id ){
        // $venta_id = $request->venta_id;
        // $recepcion_id = $request->recepcion_id;

        $venta = Venta::join('recepcions','ventas.recepcion_id', 'recepcions.id')->join('huespeds','recepcions.huesped_id','huespeds.id')->join('habitacions','recepcions.habitacion_id','habitacions.id')->join('tipo_habitacions','habitacions.tipo_habitacion_id','tipo_habitacions.id')->select('ventas.id','ventas.recepcion_id','recepcions.fecha_entrada','recepcions.fecha_salida', 'recepcions.cant_dias','recepcions.monto','huespeds.nombre as nombre_huesped','huespeds.num_documento','huespeds.celular','huespeds.procedencia','habitacions.numero','habitacions.precio','habitacions.estado','tipo_habitacions.nombre as tipo','tipo_habitacions.imagen')->orderBy('ventas.id','asc')->where('ventas.recepcion_id','=', $recepcion_id)->where('ventas.id','=',$venta_id)->get();

        $detalle = DetalleRecepcion::join('productos','detalle_recepcions.producto_id','productos.id')->join('categorias','productos.categoria_id','categorias.id')->select('detalle_recepcions.id','detalle_recepcions.cantidad', 'detalle_recepcions.estado','detalle_recepcions.precio','productos.nombre as producto', 'productos.codigo', 'categorias.nombre as categoria')->where('recepcion_id','=',$recepcion_id)->get();

            $fecha_actual = Carbon::now('America/Lima');
            $nombreImpresora = "EPSON TM-T20II Receipt";
            $connector = new WindowsPrintConnector($nombreImpresora);
            $impresora = new Printer($connector);
            $impresora->setJustification(Printer::JUSTIFY_CENTER);
            $impresora->setTextSize(2, 2);
            $impresora->setEmphasis(true);
            $impresora->text("TICKET DE RECEPCIÓN \n");
            $impresora->setTextSize(1, 1);
            $impresora->setEmphasis(false);
            $impresora->text("\n");
            $impresora->text("HOSTAL LOS ÁNGELES\n");
            $impresora->text("Av. evitamiento N° 581 - El Tambo\n");
            $impresora->text("Celular: 957548436\n");
            $impresora->text("Email: heracledis12@gmail.com\n");
            $impresora->text("FECHA DE EMISIÓN: ".$fecha_actual->toDateTimeString()."\n");
            $impresora->text("JUNÍN - HUANCAYO - EL TAMBO\n");
            $impresora->text("\n======================================\n");
            $impresora->setTextSize(1, 1);
            $impresora->setEmphasis(true);
            $impresora->text("DATOS DE LA HABITACIÓN\n");
            $impresora->text("======================================\n");
            $impresora->setJustification(Printer::JUSTIFY_CENTER);
            $impresora->setEmphasis(false);
            foreach ($venta as $v ) {
                # code...
                $impresora->text("\nNÚMERO DE LA HABITACIÓN: #".$v->numero."\n");
                $impresora->text("TIPO DE HABITACIÓN: ".$v->tipo."\n");
                $impresora->text("NOMBRE DE HUESPED: ".$v->nombre_huesped."\n");
                $impresora->text("DNI: ".$v->num_documento."\n");
                $impresora->text("CELULAR: ".$v->celular."\n");
                $impresora->text("LUGAR DE PROCEDENCIA: ".$v->procedencia."\n");
                $impresora->text("FECHA DE INGRESO: ".$v->fecha_entrada."\n");
            }

            $impresora->setJustification(Printer::JUSTIFY_CENTER);
            $impresora->text("\n======================================\n");
            $impresora->setTextSize(1, 1);
            $impresora->setEmphasis(true);
            $impresora->text("DETALLES ADICIONALES A LA HABITACIÓN\n");
            $impresora->text("======================================\n");
            if (count($detalle) === 0) {
                # code...
                $impresora->setEmphasis(false);
                $impresora->text("No compro productos adicionales\n");
            }
            foreach ($detalle as $det ) {
                $impresora->setEmphasis(false);
                $impresora->setJustification(Printer::JUSTIFY_LEFT);
                $impresora->text("- ".sprintf("%.2fx%s", $det->cantidad, $det->producto));
                $impresora->text("......................");
                $impresora->setJustification(Printer::JUSTIFY_RIGHT);
                $impresora->text('S/. ' . number_format($det->cantidad*$det->precio, 2) . "\n");

            }
            $impresora->setJustification(Printer::JUSTIFY_LEFT);
            // $impresora->text("======================================\n");
            foreach ($venta as $v ) {
                $impresora->text("\nCOSTO DE LA HABITACIÓN:............... S/. ".sprintf("%.2f",$v->precio)."\n");

            }
            $impresora->setJustification(Printer::JUSTIFY_RIGHT);
            $impresora->setTextSize(1, 1);
            $impresora->setEmphasis(true);
            $impresora->text("MONTO TOTAL: S/. " . number_format($v->monto, 2) . "\n");
            $impresora->setJustification(Printer::JUSTIFY_CENTER);
            $impresora->setTextSize(1, 2);
            $impresora->setEmphasis(true);
            $impresora->text("-------------------------------------------------\n");
            $impresora->text("^-^ GRACIAS POR SU PREFERENCIA ^-^");
            $impresora->feed(2);
            $impresora->cut();
            $impresora->pulse();
            $impresora->close();
    }
}
