<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();

// });




Route::get('habitacion', 'HabitacionController@index');
Route::get('habitacion/selecthabitacion', 'HabitacionController@selectHabitacion');
Route::get('habitacion/gethabitacion', 'HabitacionController@getHabitacion');
Route::get('habitacion/gethabitacionocupada', 'HabitacionController@selectHabitacionOcupada');
Route::get('habitacion/gethabitacioncheck', 'HabitacionController@selectHabitacionCheckOut');
Route::post('habitacion/guardar', 'HabitacionController@store');
Route::put('habitacion/actualizar', 'HabitacionController@update');
Route::put('habitacion/eliminar', 'HabitacionController@eliminar');
Route::put('habitacion/actualizarEstado', 'HabitacionController@actualizarEstado');


Route::get('huesped', 'HuespedController@index');
Route::post('huesped/guardar', 'HuespedController@store');
Route::put('huesped/actualizar', 'HuespedController@update');
Route::put('huesped/eliminar', 'HuespedController@eliminar');

Route::get('categoria', 'CategoriaController@index');
Route::post('categoria/guardar', 'CategoriaController@store');
Route::post('categoria/getcategoria', 'CategoriaController@getCategoria');
Route::put('categoria/actualizar', 'CategoriaController@update');
Route::put('categoria/desactivar', 'CategoriaController@desactivar');
Route::put('categoria/activar', 'CategoriaController@activar');

Route::get('producto', 'ProductoController@index');
Route::post('producto/guardar', 'ProductoController@store');
// Route::post('producto/getproducto', 'ProductoController@getproducto');
Route::put('producto/actualizar', 'ProductoController@update');
Route::put('producto/eliminar', 'ProductoController@eliminar');
Route::get('producto/listarproductos', 'ProductoController@listarProducto');


Route::post('recepcion/guardar', 'RecepcionController@guardarRecepcion');
Route::post('recepcion/actualizar', 'RecepcionController@actualizarRecepcion');
Route::get('recepcion/getrecepcion', 'RecepcionController@getRecepcion');
Route::get('recepcion/getrecepcioncheckout', 'RecepcionController@getRecepcionCheckOut');
Route::get('prueba', 'RecepcionController@prueba');

Route::get('venta', 'VentaController@index');
Route::get('venta/obtenerDatos', 'VentaController@getData');
Route::get('venta/imprimirpdf/{id}-{recepcion_id}', 'VentaController@listarPdf')->name('venta_pdf');
Route::get('venta/ventaspdf/{buscar}', 'VentaController@ventasPDF');
Route::post('venta/registrar', 'VentaController@generarVenta');
Route::put('venta/anular', 'VentaController@anular');
Route::post('venta/imprimir/{id}-{recepcion_id}', 'VentaController@impresora');

Route::get('usuario', 'UserController@index');
Route::get('usuario/datos', 'UserController@getCantidades');
Route::post('usuario/crear','UserController@store');
Route::put('usuario/eliminar','UserController@destroy');
Route::put('usuario/actualizar','UserController@update');


Route::post('/login', 'UserController@autenticate');

Route::group(['prefix' => 'admin', 'middleware' => ['jwt.verify']], function () {
    Route::get('/getuser', 'UserController@getUserAutenticado');

    Route::get('tipo', 'TipoHabitacionController@index');
    Route::get('tipo/gettipo', 'TipoHabitacionController@getTipoHabitacion');
    Route::post('tipo/guardar', 'TipoHabitacionController@store');
    Route::put('tipo/actualizar', 'TipoHabitacionController@update');
    Route::put('tipo/eliminar', 'TipoHabitacionController@eliminar');

    Route::get('rol', 'RolController@index');
    Route::get('rol/getrol', 'RolController@getRol');
    Route::post('rol/guardar', 'RolController@store');
    Route::put('rol/actualizar', 'RolController@update');
    Route::put('rol/desactivar', 'RolController@desactivar');
    Route::put('rol/activar', 'RolController@activar');


});
