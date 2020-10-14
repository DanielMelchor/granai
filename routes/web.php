<?php

use Illuminate\Support\Facades\Route;

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

Route::get('lang/{lang}', 'LanguageController@swap')->name('lang.swap');

Route::get('/welcome','HomeController@welcome')->name('welcome');

Route::get('/', function () {
    return view('welcome');
});

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
Route::get('/home', 'HomeController@index')->name('home');
//Route::get('/', 'HomeController@inicio')->name('inicio');
//Route::get('/','AgendaController@nuevo_index')->name('nueva_agenda');
//Route::get('/welcome', 'HomeController@welcome')->name('welcome');
//Route::get('calendario', 'HomeController@calendario')->name('calendario');

Auth::routes();

/*Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles','RoleController');
    Route::resource('users','UserController');
});*/
Route::group(['prefix' => 'admisiones'], function () {
	Route::get('listado','AdmisionController@index')->name('admisiones');
	Route::get('nueva_admision/{paciente_id}/{origen}','admisionController@nueva_admision')->name('nueva_admision');
	Route::post('grabar','AdmisionController@store')->name('grabar_admision');
	Route::get('editar/{admision_id}','admisionController@edit')->name('editar_admision');
	Route::post('actualizar_admision/{admision_id}','AdmisionController@update')->name('actualizar_admision');
	Route::post('actualizar_admision','AdmisionController@update_ajax')->name('actualizar_admision_ajax');
	//Route::get('cerrar_admision/{admision_id}','AdmisionController@cerrar_admision')->name('cerrar_admision');
	Route::post('cerrar_admision','AdmisionController@cerrar_admision_ajax')->name('cerrar_admision');
	Route::get('generar_receta/{admision_id}','AdmisionController@receta')->name('generar_receta');
	Route::get('generar_informe/{admision_id}','AdmisionController@informe')->name('generar_informe');
	Route::post('abrir/{admision_id}','AdmisionController@reapertura')->name('reapertura_admision');
	Route::post('trae_consulta', 'AdmisionController@trae_consulta')->name('trae_consulta');
	Route::post('trae_egreso', 'AdmisionController@trae_egreso')->name('trae_egreso');
	Route::post('trae_procedimiento', 'AdmisionController@trae_procedimiento')->name('trae_procedimiento');
	Route::post('trae_imagenes_procedimiento', 'AdmisionController@trae_imagenes_procedimiento')->name('trae_imagenes_procedimiento');
	Route::post('Admision_SubirImagen','AdmisionController@SubirImagen')->name('Admision_SubirImagen');
	Route::post('Admision_nSubirImagen','AdmisionController@nSubirImagen')->name('Admision_nSubirImagen');
	Route::post('trae_cargos', 'AdmisionController@trae_cargos')->name('trae_cargos');
	Route::post('generales_factura', 'AdmisionController@trae_datos_para_factura')->name('trae_datos_para_factura');
	Route::post('ultimaconsulta_ajax', 'AdmisionController@ultimaconsulta_ajAx')->name('ultimaconsulta_ajax');
	Route::post('ultimoegreso_ajax', 'AdmisionController@ultimoegreso_Ajax')->name('ultimoegreso_ajax');
	Route::post('ultimoprocedimiento_ajax', 'AdmisionController@ultimoprocedimiento_ajax')->name('ultimoprocedimiento_ajax');
	Route::post('actconsulta_ajax', 'AdmisionController@update_consulta_ajax')->name('actconsulta_ajax');
	Route::post('actprocedimiento_ajax','AdmisionController@update_procedimiento_ajax')->name('actprocedimiento_ajax');
	Route::post('acthospitalizacion_ajax', 'AdmisionController@update_hospitalizacion_ajax')->name('acthospitalizacion_ajax');
	Route::post('imagen_informe', 'AdmisionController@imagen_informe')->name('imagen_informe');
	/*
	Route::get('nueva_admision/{paciente_id}','admisionController@nueva_admision')->name('nueva_admision');
	Route::get('admision_cargos_listado','admisionController@index_cargos')->name('admision_cargos_listado');
	Route::post('Admision_SubirImagen','admisionController@SubirImagen')->name('Admision_SubirImagen');
	Route::post('marcar_Imagen','admisionController@MarcarImagen')->name('Admision_MarcarImagen');
	Route::get('listado_cerradas','admisionController@index_cerradas')->name('admisiones_cerradas');
	Route::get('listado_sin_factura','admisionController@index_sin_factura')->name('admisiones_sin_factura');
	Route::get('agregar','admisionController@create')->name('crear_admision');
	
	Route::post('actualizar_nuevo/{admision_id}','admisionController@update_nuevo')->name('update_nueva_admision');
	Route::post('actualizar_consulta','admisionController@update_nueva_consulta')->name('update_nueva_consulta');
	Route::post('actualizar_procedimiento','admisionController@update_nuevo_procedimiento')->name('update_nuevo_procedimiento');
	Route::post('actualizar_egreso','admisionController@update_nuevo_egreso')->name('update_nuevo_egreso');
	Route::get('cerrar_admision/{admision_id}','admisionController@cerrar_admision')->name('cerrar_admision');
	Route::get('editar/{admision_id}','admisionController@edit')->name('editar_admision');
	Route::post('grabar_devolucion/{admision_id}','admisionController@devolucion_store')->name('grabar_devolucion');
	Route::get('abrir/{admision_id}','admisionController@reapertura')->name('reapertura_admision');
	Route::get('actualizar_admision/{admision_id}','admisionController@update')->name('actualizar_admision');
	Route::post('trae_consulta', 'admisionController@trae_consulta')->name('trae_consulta');
	Route::post('trae_procedimiento', 'admisionController@trae_procedimiento')->name('trae_procedimiento');
	Route::post('trae_egreso', 'admisionController@trae_egreso')->name('trae_egreso');
	Route::post('actconsulta_ajax', 'admisionController@update_consulta_ajax')->name('actconsulta_ajax');
	Route::post('actprocedimiento_ajax', 'admisionController@update_procedimiento_ajax')->name('actprocedimiento_ajax');
	Route::post('acthospitalizacion_ajax', 'admisionController@update_hospitalizacion_ajax')->name('acthospitalizacion_ajax');
	Route::post('ultimaconsulta_ajax', 'admisionController@ultimaconsulta_ajax')->name('ultimaconsulta_ajax');
	Route::post('ultimoegreso_ajax', 'admisionController@ultimoegreso_ajax')->name('ultimoegreso_ajax');
	Route::post('ultimoprocedimiento_ajax', 'admisionController@ultimoprocedimiento_ajax')->name('ultimoprocedimiento_ajax');
	Route::get('receta/{admision_id}','admisionController@impresion_receta')->name('receta');
	*/
});

Route::group(['prefix' => 'agenda'], function () {
	Route::post('grabar','AgendaController@store')->name('grabar_agenda');
	Route::post('actualizar','AgendaController@update')->name('actualizar_agenda');
	Route::get('nueva_agenda','AgendaController@nuevo_index')->name('nueva_agenda');
	Route::post('grabar','AgendaController@nuevo_store')->name('nuevo_grabar_agenda');
	Route::get('edicion/{cita_id}','AgendaController@nuevo_edit')->name('nueva_edicion');
	Route::post('actualizar/{cita_id}','AgendaController@update_nuevo')->name('actualizar_nueva_agenda');
	Route::post('crea_admision', 'AgendaController@store_admision_x_cita')->name('crea_admision_x_cita');
	//Route::get('cancelar/{cita_id}','AgendaController@marcar_cancelada')->name('cancelar_cita');
	Route::post('cancelar','AgendaController@marcar_cancelada_ajax')->name('cancelar_cita');
	//Route::get('realizar/{cita_id}','AgendaController@marcar_realizada')->name('realizar_cita');
	Route::post('realizar','AgendaController@marcar_realizada_ajax')->name('realizar_cita');
	Route::post('citas','AgendaController@trae_citas')->name('trae_citas');
	/*Route::get('listado','AgendaController@index')->name('agenda');
	Route::get('agregar','AgendaController@create')->name('crear_agenda');
	Route::post('grabar','AgendaController@store')->name('grabar_agenda');
	Route::get('editar/{agenda_id}','AgendaController@edit')->name('editar_agenda');
	Route::post('actualizar','AgendaController@update')->name('actualizar_agenda');
	Route::get('anular','AgendaController@destroy')->name('anular_agenda');
	Route::get('nueva_agenda/{medico_id}/{estado}/{fecha}','AgendaController@nuevo_index')->name('nueva_agenda');
	Route::post('grabar','AgendaController@nuevo_store')->name('nuevo_grabar_agenda');
	Route::post('actualizar/{cita_id}','AgendaController@update_nuevo')->name('actualizar_nueva_agenda');
	Route::get('edicion/{cita_id}','AgendaController@nuevo_edit')->name('nueva_edicion');
	Route::get('cancelar/{cita_id}','AgendaController@marcar_cancelada')->name('cancelar_cita');
	Route::get('realizar/{cita_id}','AgendaController@marcar_realizada')->name('realizar_cita');*/
});

Route::group(['prefix' => 'aseguradoras'], function () {
	Route::get('listado','aseguradoraController@index')->name('aseguradoras');
	Route::get('agregar','aseguradoraController@create')->name('crear_aseguradora');
	Route::post('grabar','aseguradoraController@store')->name('grabar_aseguradora');
	Route::get('editar/{aseguradora_id}','aseguradoraController@edit')->name('editar_aseguradora');
	Route::post('actualizar/{aseguradora_id}','aseguradoraController@update')->name('actualizar_aseguradora');
});

Route::group(['prefix' => 'bancos'], function () {
	Route::get('listado','BancoController@index')->name('bancos');
	Route::get('agregar','BancoController@create')->name('crear_banco');
	Route::post('grabar','BancoController@store')->name('grabar_banco');
	Route::get('editar/{Banco_id}','BancoController@edit')->name('editar_banco');
	Route::post('actualizar/{Banco_id}','BancoController@update')->name('actualizar_banco');
	Route::post('formas_de_pago','BancoController@trae_formas_pago')->name('formas_de_pago');
});

Route::group(['prefix' => 'cajas'], function () {
	Route::get('listado','CajaController@index')->name('cajas');
	Route::get('agregar','CajaController@create')->name('crear_caja');
	Route::post('grabar','CajaController@store')->name('grabar_caja');
	Route::get('editar/{Caja_id}','CajaController@edit')->name('editar_caja');
	Route::get('show/{Caja_id}','CajaController@show')->name('resolucion_caja');
	Route::post('actualizar','CajaController@update')->name('actualizar_caja');
	Route::post('resolucion_serie','CajaController@resolucion_x_serie')->name('trae_resolucion_x_serie');
	Route::post('resolucion_factura_x_caja','CajaController@resolucion_factura_x_caja')->name('resolucion_factura_x_caja');
	Route::post('resolucion_recibo_x_caja','CajaController@resolucion_recibo_x_caja')->name('resolucion_recibo_x_caja');
	Route::post('caja_resoluciones','CajaController@caja_resoluciones')->name('caja_resoluciones');
	Route::post('resolucion_utilizada','CajaController@resolucion_registros_utilizados')->name('resolucion_registros_utilizados');
	Route::post('cajas_por_empresa','CajaController@cajas_x_empresa')->name('cajas_por_empresa');
});

Route::group(['prefix' => 'correlativos'], function () {
	Route::get('listado','CorrelativoController@index')->name('correlativos');
	Route::get('agregar','CorrelativoController@create')->name('crear_correlativo_1');
	Route::post('grabar','CorrelativoController@store')->name('grabar_correlativo_1');
	Route::get('editar/{correlativo_id}','CorrelativoController@edit')->name('editar_correlativo_1');
	Route::post('actualizar/{correlativo_id}','CorrelativoController@update')->name('actualizar_correlativo_1');
});

Route::group(['prefix' => 'dosis'], function () {
	Route::get('listado','dosisController@index')->name('dosis');
	Route::get('agregar','dosisController@create')->name('crear_dosis');
	Route::post('grabar','dosisController@store')->name('grabar_dosis');
	Route::get('editar/{dosis_id}','dosisController@edit')->name('editar_dosis');
	Route::post('actualizar/{dosis_id}','dosisController@update')->name('actualizar_dosis');
});

Route::group(['prefix' => 'empresas'], function () {
	Route::get('listado','empresaController@index')->name('empresas');
	Route::get('agregar','empresaController@create')->name('crear_empresa');
	Route::post('grabar','empresaController@store')->name('grabar_empresa');
	Route::get('editar/{empresa_id}','empresaController@edit')->name('editar_empresa');
	Route::post('actualizar/{empresa_id}','empresaController@update')->name('actualizar_empresa');
	Route::get('borrar_logo/{empresa_id}', 'empresaController@borrar_logo')->name('borrar_logo');
});

Route::group(['prefix' => 'especialidades'], function () {
	Route::get('listado','especialidadController@index')->name('especialidades');
	Route::get('agregar','especialidadController@create')->name('crear_especialidad');
	Route::post('grabar','especialidadController@store')->name('grabar_especialidad');
	Route::get('editar/{especialidad_id}','especialidadController@edit')->name('editar_especialidad');
	Route::post('actualizar/{especialidad_id}','especialidadController@update')->name('actualizar_especialidad');
});

Route::group(['prefix' => 'formas_pago'], function () {
	Route::post('campos_requeridos','FormaPagoController@campos_requeridos')->name('campos_requeridos');
});

Route::group(['prefix' => 'hospitales'], function () {
	Route::get('listado','hospitalController@index')->name('hospitales');
	Route::get('agregar','hospitalController@create')->name('crear_hospital');
	Route::post('grabar','hospitalController@store')->name('grabar_hospital');
	Route::get('editar/{hospital_id}','hospitalController@edit')->name('editar_hospital');
	Route::get('detalle/{hospital_id}','hospitalController@show')->name('detalle_hospital');
	Route::post('actualizar/{hospital_id}','hospitalController@update')->name('actualizar_hospital');
});

Route::group(['prefix' => 'medicamentos'], function () {
	Route::get('listado','medicamentoController@index')->name('medicamentos');
	Route::get('agregar','medicamentoController@create')->name('crear_medicamento');
	Route::post('grabar','medicamentoController@store')->name('grabar_medicamento');
	Route::get('editar/{medicamento_id}','medicamentoController@edit')->name('editar_medicamento');
	Route::get('detalle/{medicamento_id}','medicamentoController@show')->name('detalle_medicamento');
	Route::post('actualizar/{medicamento_id}','medicamentoController@update')->name('actualizar_medicamento');
	Route::post('baja/{medicamentodosis_id}','medicamentoController@destroy')->name('baja_dosis');
	Route::post('trae_dosis_medicamento','medicamentoController@trae_dosis_medicamento')->name('trae_dosis_medicamento');
	Route::post('receta_descripcion','medicamentoController@receta')->name('receta_descripcion');
});

Route::group(['prefix' => 'medicos'], function () {
	Route::get('listado','medicoController@index')->name('medicos');
	Route::get('agregar','medicoController@create')->name('crear_medico');
	Route::post('grabar','medicoController@store')->name('grabar_medico');
	Route::get('editar/{medico_id}','medicoController@edit')->name('editar_medico');
	Route::post('actualizar/{medico_id}','medicoController@update')->name('actualizar_medico');
	Route::get('borrar_foto_medico/{medico_id}', 'medicoController@borrar_firma')->name('borrar_foto_medico');
	Route::post('existe_config_receta', 'medicoController@existe_config_receta_ajax')->name('existe_config_receta');
	Route::post('grabar_config_receta', 'medicoController@store_config_receta_ajax')->name('grabar_config_receta');
	Route::post('actualizar_config_receta', 'medicoController@update_config_receta_ajax')->name('actualizar_config_receta');
});

Route::group(['prefix' => 'motivosAnulacion'], function () {
	Route::get('listado','MotivoAnulacionController@index')->name('motivosAnulacion');
	Route::get('agregar','MotivoAnulacionController@create')->name('crear_motivoanulacion');
	Route::post('grabar','MotivoAnulacionController@store')->name('grabar_motivoanulacion');
	Route::get('editar/{anulacion_id}','MotivoAnulacionController@edit')->name('editar_motivoanulacion');
	Route::post('actualizar/{anulacion_id}','MotivoAnulacionController@update')->name('actualizar_motivoanulacion');
});

Route::group(['prefix' => 'motivoRechazos'], function () {
	Route::get('listado','MotivoRechazoController@index')->name('motivoRechazos');
	Route::get('agregar','MotivoRechazoController@create')->name('crear_motivorechazo');
	Route::post('grabar','MotivoRechazoController@store')->name('grabar_motivorechazo');
	Route::get('editar/{rechazo_id}','MotivoRechazoController@edit')->name('editar_motivorechazo');
	Route::post('actualizar/{rechazo_id}','MotivoRechazoController@update')->name('actualizar_motivorechazo');
});

Route::group(['prefix' => 'observaciones'], function () {
	Route::get('listado','observacionesController@index')->name('observaciones');
	Route::get('agregar','observacionesController@create')->name('crear_observacion');
	Route::post('grabar','observacionesController@store')->name('grabar_observacion');
	Route::get('editar/{proceso}','observacionesController@edit')->name('editar_observacion');
	Route::post('actualizar/{proceso}','observacionesController@update')->name('actualizar_observacion');
});

Route::group(['prefix' => 'pacientes'], function () {
	Route::get('listado','pacienteController@index')->name('pacientes');
	Route::get('agregar/{origen}/{cita_id}','pacienteController@create')->name('crear_paciente');
	Route::post('grabar/{origen}/{cita_id}','pacienteController@store')->name('grabar_paciente');
	Route::get('editar/{paciente_id}','pacienteController@edit')->name('editar_paciente');
	Route::post('actualizar/{paciente_id}','pacienteController@update')->name('actualizar_paciente');
	Route::get('paciente_admision/{paciente_id}','pacienteController@show')->name('paciente_admision');
	Route::get('paciente_admision_1/{paciente_id}/{admision_id}/{admision_tipo}','pacienteController@show1')->name('paciente_admision_1');
	Route::get('consultas/{paciente_id}', 'pacienteController@consultas')->name('paciente_consultas');
	Route::post('datos_facturacion','pacienteController@trae_datos_facturacion')->name('datos_facturacion');
	Route::post('verificar_expediente','pacienteController@verifica_expediente')->name('verificar_expediente');
});

Route::group(['prefix' => 'permissions'], function(){
	Route::get('listado', 'PermissionController@index')->name('permisos');	
	Route::get('editar/{permiso_id}', 'PermissionController@edit')->name('editar_permiso');
	Route::post('grabar', 'PermissionController@store')->name('grabar_permiso');
	Route::post('actualizar/{permiso_id}', 'PermissionController@update')->name('actualizar_permiso');
});

Route::group(['prefix' => 'productos'], function () {
	Route::get('listado','productoController@index')->name('productos');
	Route::get('agregar','productoController@create')->name('crear_producto');
	Route::post('grabar','productoController@store')->name('grabar_producto');
	Route::get('editar/{producto_id}','productoController@edit')->name('editar_producto');
	Route::post('actualizar/{producto_id}','productoController@update')->name('actualizar_producto');
	Route::post('descripcion','productoController@descripcion')->name('descripcion');
});

Route::group(['prefix' => 'reportes'], function(){
	Route::get('admisiones_activas/{fecha_inicial}/{fecha_final}/{tipo_admision}', 'ReporteController@adm_act_idx')->name('rpt_admisiones_activas');
	Route::get('impresion/{factura_id}', 'ReporteController@factura_pdf')->name('factura_pdf');
	Route::get('admisiones_activas_pdf/{fecha_inicial}/{fecha_final}/{tipo_admision}', 'ReporteController@adm_act_pdf')->name('rpt_admisiones_activas_pdf');
	Route::get('admisiones_activas_xls/{fecha_inicial}/{fecha_final}/{tipo_admision}','ReporteController@adm_act_xls')->name('rpt_admisiones_activas_xls');
	Route::get('admision_consultas/{fecha_inicial}/{fecha_final}', 'ReporteController@adm_cons_idx')->name('rpt_admision_consultas');
	Route::get('admision_consultas_pdf/{fecha_inicial}/{fecha_final}', 'ReporteController@adm_cons_pdf')->name('rpt_admision_consultas_pdf');
	Route::get('admision_consultas_xls/{fecha_inicial}/{fecha_final}','ReporteController@adm_cons_xls')->name('rpt_admision_consultas_xls');
	Route::get('admision_hospitalizacion/{fecha_inicial}/{fecha_final}', 'ReporteController@adm_hosp_idx')->name('rpt_admision_hospitalizacion');
	Route::get('admision_hospitalizacion_pdf/{fecha_inicial}/{fecha_final}', 'ReporteController@adm_hosp_pdf')->name('rpt_admision_hospitalizacion_pdf');
	Route::get('admision_hospitalizacion_xls/{fecha_inicial}/{fecha_final}','ReporteController@adm_hosp_xls')->name('rpt_admision_hospitalizacion_xls');
	Route::get('admision_procedimientos/{fecha_inicial}/{fecha_final}', 'ReporteController@adm_proc_idx')->name('rpt_admision_procedimientos');
	Route::get('admision_procedimientos_pdf/{fecha_inicial}/{fecha_final}', 'ReporteController@adm_proc_pdf')->name('rpt_admision_procedimientos_pdf');
	Route::get('admision_procedimientos_xls/{fecha_inicial}/{fecha_final}','ReporteController@adm_proc_xls')->name('rpt_admision_procedimientos_xls');
	Route::get('antiguedad_saldos', 'ReporteController@antiguedad_saldos_idx')->name('rpt_antiguedad_saldos');
	Route::get('antiguedad_saldos_pdf', 'ReporteController@antiguedad_saldos_pdf')->name('rpt_antiguedad_saldos_pdf');
	Route::get('antiguedad_saldos_xls', 'ReporteController@antiguedad_saldos_xls')->name('rpt_antiguedad_saldos_xls');
	Route::get('admisiones_con_saldo', 'ReporteController@admisiones_con_saldo_idx')->name('rpt_admisiones_con_saldo');
	Route::get('admisiones_con_saldo_pdf', 'ReporteController@admisiones_con_saldo_pdf')->name('rpt_admisiones_con_saldo_pdf');
	Route::get('admisiones_con_saldo_xls', 'ReporteController@admisiones_con_saldo_xls')->name('rpt_admisiones_con_saldo_xls');
	Route::get('admisiones_por_fecha/{tipo_admision}/{fecha_inicial}/{fecha_final}', 'ReporteController@admisiones_por_fecha_idx')->name('rpt_admisiones_por_fecha');
	Route::get('admisiones_por_fecha_pdf/{tipo_admision}/{fecha_inicial}/{fecha_final}', 'ReporteController@admisiones_por_fecha_pdf')->name('rpt_admisiones_por_fecha_pdf');
	Route::get('admisiones_por_fecha_xls/{tipo_admision}/{fecha_inicial}/{fecha_final}', 'ReporteController@admisiones_por_fecha_xls')->name('rpt_admisiones_por_fecha_xls');
	Route::get('arqueo_facturas/{caja_id}/{fecha_inicial}/{fecha_final}', 'ReporteController@arqueo_factura_idx')->name('rpt_arqueo_factura');
	Route::get('arqueo_facturas_pdf/{caja_id}/{fecha_inicial}/{fecha_final}', 'ReporteController@arqueo_factura_pdf')->name('rpt_arqueo_factura_pdf');
	Route::get('arqueo_facturas_xls/{caja_id}/{fecha_inicial}/{fecha_final}', 'ReporteController@arqueo_factura_xls')->name('rpt_arqueo_factura_xls');
	Route::get('arqueo_recibos/{caja_id}/{fecha_inicial}/{fecha_final}', 'ReporteController@arqueo_recibo_idx')->name('rpt_arqueo_recibo');
	Route::get('arqueo_cheques/{caja_id}/{fecha_inicial}/{fecha_final}', 'ReporteController@arqueo_cheques_idx')->name('rpt_arqueo_cheques');
	Route::get('arqueo_cheques_pdf/{caja_id}/{fecha_inicial}/{fecha_final}', 'ReporteController@arqueo_cheques_pdf')->name('rpt_arqueo_cheques_pdf');
	Route::get('arqueo_cheques_xls/{caja_id}/{fecha_inicial}/{fecha_final}', 'ReporteController@arqueo_cheques_xls')->name('rpt_arqueo_cheques_xls');
	Route::get('arqueo_tarjetas/{caja_id}/{fecha_inicial}/{fecha_final}', 'ReporteController@arqueo_tarjetas_idx')->name('rpt_arqueo_tarjetas');
	Route::get('arqueo_tarjetas_pdf/{caja_id}/{fecha_inicial}/{fecha_final}', 'ReporteController@arqueo_tarjetas_pdf')->name('rpt_arqueo_tarjetas_pdf');
	Route::get('arqueo_tarjetas_xls/{caja_id}/{fecha_inicial}/{fecha_final}', 'ReporteController@arqueo_tarjetas_xls')->name('rpt_arqueo_tarjetas_xls');
	Route::get('rpt_cargos/{admision_id}', 'ReporteController@rpt_cargos_pdf')->name('rpt_cargos_pdf');
	Route::get('rpt_estado_cuenta/{admision_id}', 'ReporteController@rpt_estado_cuenta_pdf')->name('rpt_estado_cuenta_pdf');
	Route::get('rpt_cargos_sin_factura', 'ReporteController@rpt_cargos_sin_factura_idx')->name('rpt_cargos_sin_factura_idx');
	Route::get('rpt_cargos_sin_factura_pdf', 'ReporteController@rpt_cargos_sin_factura_pdf')->name('rpt_cargos_sin_facturar_pdf');
	Route::get('rpt_cargos_sin_factura_xls', 'ReporteController@rpt_cargos_sin_factura_xls')->name('rpt_cargos_sin_facturar_xls');
	Route::get('anulaciones/{fecha_inicial}/{fecha_final}', 'ReporteController@rpt_anulaciones_idx')->name('rpt_anulaciones');
	Route::get('anulaciones_pdf/{fecha_inicial}/{fecha_final}', 'ReporteController@rpt_anulaciones_pdf')->name('rpt_anulaciones_pdf');
	Route::get('anulaciones_xls/{fecha_inicial}/{fecha_final}', 'ReporteController@rpt_anulaciones_xls')->name('rpt_anulaciones_xls');
});

Route::group(['prefix' => 'roles'], function(){
	Route::get('listado', 'RoleController@index')->name('roles');
	Route::get('editar/{role_id}', 'RoleController@edit')->name('editar_role');
	Route::post('grabar', 'RoleController@store')->name('grabar_role');
	Route::post('actualizar/{role_id}', 'RoleController@update')->name('actualizar_role');
	Route::get('mostrar/{role_id}', 'RoleController@show')->name('mostrar_role');
});

Route::group(['prefix' => 'tipodocumentos'], function () {
	Route::get('listado','TipoDocumentoController@index')->name('tipodocumentos');
	Route::get('agregar','TipoDocumentoController@create')->name('crear_tipodocumento');
	Route::post('grabar','TipoDocumentoController@store')->name('grabar_tipodocumento');
	Route::get('editar/{tipodocto_id}','TipoDocumentoController@edit')->name('editar_tipodocumento');
	Route::post('actualizar/{tipodocto_id}','TipoDocumentoController@update')->name('actualizar_tipodocumento');
});

Route::group(['prefix' => 'unidadmedidas'], function () {
	Route::get('listado','unidadmedidaController@index')->name('unidadmedidas');
	Route::get('agregar','unidadmedidaController@create')->name('crear_unidadmedida');
	Route::post('grabar','unidadmedidaController@store')->name('grabar_unidadmedida');
	Route::get('editar/{unidad_id}','unidadmedidaController@edit')->name('editar_unidadmedida');
	Route::post('actualizar/{unidad_id}','unidadmedidaController@update')->name('actualizar_unidadmedida');
});

Route::group(['prefix' => 'ventas'], function () {
	Route::get('listado','ventaController@index')->name('documentos_listado');
	Route::get('listado_notas_credito','ventaController@index_nc')->name('nc_listado');
	Route::get('listado_notas_debito','ventaController@index_nd')->name('nd_listado');
	Route::get('nueva_factura/{admision_id}/{paciente_id}','ventaController@factura_create')->name('nueva_factura');
	Route::get('nueva_nota_credito','ventaController@nc_create')->name('nueva_nc');
	Route::get('nueva_nota_debito','ventaController@nd_create')->name('nueva_nd');
	Route::post('factura_grabar','ventaController@factura_store')->name('grabar_factura');
	Route::post('factura_renumerar/{factura_id}', 'ventaController@factura_renumerar')->name('factura_renumerar');
	Route::post('documento_renumera','ventaController@documento_renumerar')->name('documento_renumerar');
	Route::post('factura_refacturar/{factura_id}', 'ventaController@factura_refacturar')->name('factura_refacturar');
	Route::post('documento_reFactura','ventaController@documento_refacturar')->name('documento_refacturar');
	Route::post('nota_debito_grabar','ventaController@nd_store')->name('grabar_nd');
	Route::post('nota_credito_grabar','ventaController@nc_store')->name('grabar_nc');
	Route::get('editar_factura/{factura_id}/{admision_id}','ventaController@factura_edit')->name('editar_factura');
	Route::get('nota_credito_editar/{nc_id}','ventaController@nc_edit')->name('editar_nc');
	Route::post('venta_anulacion','ventaController@documento_anular')->name('documento_anular');
	Route::post('documentos_saldo','ventaController@documentos_con_saldo')->name('documentos_con_saldo');
	Route::post('nota_credito_afectos','ventaController@nc_doctos_aplicar')->name('nc_doctos_aplicar');
	Route::get('listado_cortes','ventaController@corte_idx')->name('listado_cortes');
	Route::get('nuevo_corte','ventaController@corte_create')->name('nuevo_corte');
	Route::get('editar_corte/{corte_id}','ventaController@corte_edit')->name('editar_corte');
	Route::post('grabar_corte','ventaController@corte_store')->name('grabar_corte');
	Route::post('trae_resumen_documentos','ventaController@trae_resumen_documentos')->name('trae_resumen_documentos');
	Route::post('trae_resumen_pagos','ventaController@trae_resumen_pagos')->name('trae_resumen_pagos');
	Route::post('trae_detalle_pagos','ventaController@trae_detalle_pagos')->name('trae_detalle_pagos');
	Route::post('trae_detalle_documentos','ventaController@trae_detalle_documentos')->name('trae_detalle_documentos');
});

Route::group(['prefix' => 'pagos'], function () {
	Route::get('listado','pagoController@index')->name('recibos_listado');
	Route::get('nuevo_recibo','pagoController@create')->name('nuevo_recibo');
	Route::get('editar_recibo/{recibo_id}','pagoController@edit')->name('editar_recibo');
	Route::post('grabar_recibo','pagoController@recibo_store')->name('recibo_grabar');
	Route::post('trae_recibo', 'pagoController@trae_recibo')->name('trae_recibo');
	Route::post('trae_detalle_recibo', 'pagoController@trae_detalle_recibo')->name('trae_detalle_recibo');
	Route::post('trae_pago_recibo', 'pagoController@trae_pago_recibo')->name('trae_pago_recibo');
	Route::post('recibo_anulacion/{recibo_id}','pagoController@recibo_anular')->name('recibo_anular');
	Route::post('saldo_x_recibo', 'pagoController@trae_saldo_x_recibo')->name('trae_saldo_x_recibo');
	Route::post('recibos_con_saldo', 'pagoController@trae_recibos_con_saldo')->name('recibos_con_saldo');
	Route::post('forma_pago_recibo', 'pagoController@trae_detalle_pago_x_recibo')->name('forma_pago_recibo');
});

Route::group(['prefix' => 'usuarios'], function(){
	Route::get('listado', 'UsuarioController@index')->name('usuarios');	
	Route::get('nuevo_usuario', 'UsuarioController@create')->name('crear_usuario');
	Route::get('editar/{usuario_id}', 'UsuarioController@edit')->name('editar_usuario');
	Route::post('grabar', 'UsuarioController@store')->name('grabar_usuario');
	Route::post('actualizar/{usuario_id}', 'UsuarioController@update')->name('actualizar_usuario');
	/*Route::get('contrasena', 'UsuarioController@index_contrasena')->name('contrasena');	*/
	Route::get('cambio_clave', 'UsuarioController@edit_clave')->name('cambio_clave');
	Route::post('actualizar_contrasena', 'UsuarioController@update_contrasena')->name('actualizar_contrasena');
	Route::get('inicializar_contrasena/{usuario_id}', 'UsuarioController@reset')->name('inicializar_contrasena');
});