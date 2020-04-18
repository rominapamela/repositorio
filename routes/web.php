<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();

Route::group(['middleware' => 'auth'], function(){
  Route::group(['middleware' => 'adminUser'], function () {
          Route::get('/admin', function(){
          return view('admin.home');
          })->name('admin');
      });
});

Route::group(['prefix'=>'admin','middleware' => 'auth'], function(){
  Route::group(['middleware' => 'adminUser'], function () {
 
//******************************Rutas para departamentos***********************************
  Route::resource('departamentos','DepartamentosController');
  Route::get('/departamentos/{id}/desable','DepartamentosController@desable')->name('departamentos.desable');
  Route::get('/departamentos/{id}/enable','DepartamentosController@enable')->name('departamentos.enable');

//******************************Rutas para carreras****************************************
  Route::resource('carreras','CarrerasController');
  Route::get('/carreras/{id}/desable','CarrerasController@desable')->name('carreras.desable');
  Route::get('/carreras/{id}/enable','CarrerasController@enable')->name('carreras.enable');

//******************************Rutas para materias****************************************
  Route::resource('materias','MateriasController');
  Route::get('/materias/{id}/desable','MateriasController@desable')->name('materias.desable');
  Route::get('/materias/{id}/enable','MateriasController@enable')->name('materias.enable');

  Route::get('carrerasjs/{id}','MateriasController@getCarreras');

//******************************Rutas para users****************************************

  Route::resource('users','UsersController');
  Route::get('user/{tipo}','UsersController@index')->name('users.index');
  Route::get('usercreate/{tipo}','UsersController@create')->name('users.create');
  Route::get('useredit/{id}/{tipo}','UsersController@edit')->name('users.edit');
  Route::patch('userupdate/{id}/{tipo}','UsersController@update')->name('users.update');
  Route::post('userstore/{tipo}','UsersController@store')->name('users.store');
  Route::get('/users/{id}/desable','UsersController@desable')->name('users.desable');
  Route::get('/userss/{id}/enable','UsersController@enable')->name('users.enable');

  });
});

//********************************Rutas WEB***********************************
Route::get('/', function () {
    $dptos = App\Departamento::all();
    $carreras = App\Carrera::orderBy('departamento_id')->get();
    return view('home.index')->with('dptos',$dptos)
                             ->with('carreras',$carreras);
    });
Route::get('/noAutorizhed',function(){
    return view('auth.role');})->name('noAutorizhed');

Route::group(['middleware' => 'auth'], function () {
  Route::get('favoritos','FavoritosController@listaFav')->name('favoritos');
  Route::get('favoritos/{nombre}/{carrera}/{materia}/{apunte}','ApuntesController@guardaFav')->name('guardaFav.apunte');
  Route::get('/home', 'HomeController@index')->name('home');
  Route::get('dpto', 'HomeController@index')->name('dpto');
  Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
  Route::get('perfil','UsuarioController@datosUsuario')->name('perfil');
  Route::post('actualizarperfil', 'UsuarioController@store');
  //***********************Rutas de Materias Docentes***************************
  Route::resource('materiasdocente','MateriasDocenteController');

  //***********************Rutas de Apuntes*************************************
  Route::resource('apuntes','ApuntesController');
  Route::get('dpto/{nombre}','VistaCarrerasController@mostrarVistaCarreras')->name('dpto.carreras');
  Route::get('dpto/{nombre}/{carrera}','VistaMateriasController@mostrarVistaMaterias')->name('dpto.materias');
  Route::get('dpto/{nombre}/{carrera}/{materia}','VistaApuntesController@mostrarVistaApuntes')->name('dpto.apuntes');
  Route::get('dpto/{nombre}/{carrera}/{materia}/{apunte}','ApuntesController@show')->name('show.apunte');
  Route::get('mostrar/{carrera}/{materia}/{apunte}','ApuntesController@unapunte')->name('unshow.apunte');

  Route::group(['middleware' => 'standard'], function () { 
    //**********Rutas exclusivas de Docentes ***********
    Route::get('subida','ApuntesController@create')->name('subida');
    Route::post('subirapunte','ApuntesController@store');
    
    Route::get('historial','ApuntesController@index')->name('historial');
    Route::get('modificar/{id_apunte}', 'ApuntesController@datos');
    Route::post('modificarapunte/{id_apunte}', 'ApuntesController@update');
    Route::get('eliminar/{id_apunte}', 'ApuntesController@eliminar');
    Route::post('cambiaremail', 'UsuarioController@cambiaremail');
  });
});

