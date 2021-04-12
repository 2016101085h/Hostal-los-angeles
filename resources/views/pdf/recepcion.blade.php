<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
       {{-- <link
           href="https://fonts.googleapis.com/css2?family=Baloo+Paaji+2:wght@400;500;600;700;800&family=Open+Sans:wght@700&display=swap"
           rel="stylesheet"> --}}
    <title>PDF RECEPCIÓN</title>

</head>
<style>
    body{
        font-size: 7px
    }


 .contenedor:after{
 clear: both;
 content: "";
 display: block;
 }




</style>

<body>
    @foreach ($ventas as $v)


    <div class="modal-body">
        <h2 style="text-align: center">TICKET DE RECEPECIÓN</h2>

            <div class="float">
                 <div style="float:left; text-align: right;" style="margin: 10px;margin-right: 20px;">


                    </div>
                 <div style="text-align: center">
                     {{--  {{now()::setLocale('es')}}  --}}
                     <p><b>Fecha: </b>{{now()->format('l jS \\of F Y h:i:s A')}}</p>

                     <p class="p"><b>Celular</b>: 9575874</p>
                     <p class="p"><b>Email:</b> hercledis12@gmail</p>
                     <p class="p"><b>Direccion: </b> Avenida evitamiento sur #581 <span class="p">El tambo - Huancayo</span></p>
                 </div>
            </div>
            <h4 style="text-transform: uppercase; text-align: center">Datos de la Habitación</h4>
            <hr>
            <div class="contenedor" >
                <div style="width: 50%; float: left">
                    <p><b>Numero: </b>{{$v->numero}}</p>
                    <p><b>Tipo de Habitación: </b>{{$v->tipo}}</p>
                    <p><b>Costo: </b>S/. {{$v->precio}}</p>
                    <p><b>Fecha Entrada: </b>{{$v->fecha_entrada}}</p>
                    <p><b>Fecha Salida: </b>{{$v->fecha_salida}}</p>

                </div>
                <div style="width: 50%; float: right ">
                    <p><b>Nombre Huesped: </b>{{$v->nombre_huesped}}</p>
                    <p><b>DNI: </b>{{$v->num_documento}}</p>
                    <p><b>Celular: </b>{{$v->celular}}</p>
                    <p><b>Procedencia: </b>{{$v->procedencia}}</p>


                </div>

                {{-- <p><b>Nombre de referecnia: </b>{{$ped->nombre}}</p>

                <p><b>Fecha de pedido: </b>{{$ped->fecha_hora}}</p>
                <p><b>Tipo de pago: </b>{{$ped->tipo_pago}}</p>
                <p><b>Dirección: </b>{{$ped->direccion}}</p> --}}
            </div>


            <h4 style="font-weight: bold;text-transform: uppercase; text-align: center">Detalles Adicioanles a la Habitación</h4>
            <hr>
        @endforeach
        <table  border="0" style="margin: 0 auto">
            <tr style="border: 2px solid #000">
                <th >#</th>
                <th>Código</th>
                <th>Producto</th>
                <th>Categoria</th>
                <th>Precio Unitario</th>

                <th>Cantidad</th>
                <th>Subtotal</th>
                {{-- <th class="">Subtotal</th> --}}
            </tr>


                @foreach ($detalles as $index=>$det)
                <tr>
                    <td>{{$index + 1}}</td>
                    <td>{{$det->codigo}}</td>
                    <td>{{$det->producto}}</td>
                    <td>{{$det->categoria}}</td>
                    <td>S/. {{$det->precio}}</td>
                    <td>{{$det->cantidad}}</td>
                    {{-- <td>{{'S/. '.$det->precio}}</td> --}}
                    <td>{{'S/. '.round(($det->cantidad*$det->precio), 3)}}</td>
                </tr>



                @endforeach
                <tr style="border: 1px solid #000">
                    <td colspan="6" style="text-align: right;"><b>Precio de la Habaitación</b></td>
                    <td class="">S/. {{$v->precio}}</td>
                </tr>

                @if (count($detalles) === 0)
                    <tr style="text-align: center; background: #e1e1e1">
                        <td colspan="7">No tiene productos adicionales comprados</td>
                    </tr>
                @endif

                    @foreach ($ventas as $v)

                    @endforeach
            <tr style="border: 1px solid #000">
                <td colspan="5" style="text-align: right;"><b>Monto Total</b></td>
                <td colspan="4">S/. {{$v->monto}}</td>
            </tr>



        </table>


    </div>
</body>

</html>
