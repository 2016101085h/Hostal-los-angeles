<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
       {{-- <link
           href="https://fonts.googleapis.com/css2?family=Baloo+Paaji+2:wght@400;500;600;700;800&family=Open+Sans:wght@700&display=swap"
           rel="stylesheet"> --}}
    <title>PDF VENTAS</title>

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



    <div class="modal-body">
        <h2 style="text-align: center">VENTA GENERADAS</h2>

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




            <h4 style="font-weight: bold;text-transform: uppercase; text-align: center">Detalles de ventas realizadas</h4>
            <hr>


        <table  border="0" style="margin: 0 auto">
            <tr style="border: 2px solid #000">
                <th >#</th>
                <th>Fecha y Hora</th>
                <th>Tipo de Pago</th>

                <th>Tipo de comprobante</th>
                <th>Monto</th>
                {{-- <th class="">Subtotal</th> --}}
            </tr>
            @foreach ($ventas as $index=>$v)
                <tr>
                    <td>{{$index + 1}}</td>
                    <td>{{$v->fecha_hora}}</td>
                    <td>{{$v->medio_pago}}</td>

                    <td>{{$v->comprobante}}</td>
                    <td>S/. {{$v->total}}</td>
                </tr>
            @endforeach







        </table>
        <div style="text-align: right">
            <p><b>Total: </b>S/. {{$total}}0</p>
        </div>



    </div>
</body>

</html>
