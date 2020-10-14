<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromView;
use App\Exports\AdmisionesActivasExport;
use App\Exports\AdmisionConsultasExport;
use App\Exports\AdmisionHospitalizacionesExport;
use App\Exports\AdmisionProcedimientosExport;
use App\Exports\AntiguedadSaldosExport;
use App\Exports\AdmisionesConSaldoExport;
use App\Exports\AdmisionesPorFechaExport;
use App\Exports\variosExport;
use DB;
use Auth;
use PDF;
use Carbon\carbon;
use App\admision;
use App\admision_cargo;
use App\empresa;
use App\Caja;
use App\maestro_documento;
use App\detalle_documento;

class ReporteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function adm_act_idx($fecha_inicial, $fecha_final, $tipo_admision){
    	$inicio = Carbon::now()->startOfMonth()->format('Y-m-d');
      $hoy = Carbon::now()->format('Y-m-d');
    	if ($tipo_admision == 'T') {
        $admisiones = DB::table('admisiones as a')
                      ->join('users as u', 'a.created_by', 'u.id')
                      ->join('pacientes as p', 'a.paciente_id', 'p.id')
                      ->select('a.id', 'a.admision', 'a.fecha', 'p.expediente_no', 'p.nombre_completo as paciente_nombre', DB::raw('(CASE when a.tipo_admision = "C" then "Consulta" when a.tipo_admision = "P" then "Procedimiento" else "Hospitalizacion" end) as tipo_admision'), DB::raw('(CASE when a.estado = "P" then "Proceso" when a.estado = "C" then "Cerrada" else "no definido" end)as estado_descripcion'), 'u.name as usuario_nombre')
                      ->where('a.empresa_id', Auth::user()->empresa_id)
                        ->where('a.estado', 'P')
                      ->whereBetween('a.fecha',[$fecha_inicial, $fecha_final])
                      ->orderBy('a.admision', 'desc')
                      ->get();
      }else{
        $admisiones = DB::table('admisiones as a')
                      ->join('users as u', 'a.created_by', 'u.id')
                      ->join('pacientes as p', 'a.paciente_id', 'p.id')
                      ->select('a.id', 'a.admision', 'a.fecha', 'p.expediente_no', 'p.nombre_completo as paciente_nombre', DB::raw('(CASE when a.tipo_admision = "C" then "Consulta" when a.tipo_admision = "P" then "Procedimiento" else "Hospitalizacion" end) as tipo_admision'), DB::raw('(CASE when a.estado = "P" then "Proceso" when a.estado = "C" then "Cerrada" else "no definido" end)as estado_descripcion'), 'u.name as usuario_nombre')
                      ->where('a.empresa_id', Auth::user()->empresa_id)
                      ->where('tipo_admision', $tipo_admision)
                      ->where('a.estado', 'P')
                      ->whereBetween('a.fecha',[$fecha_inicial, $fecha_final])
                      ->orderBy('a.admision', 'desc')
                      ->get();
      	
      }
      return view('reportes.rpt_admision_activa_index', compact('admisiones','inicio', 'hoy', 'fecha_inicial', 'fecha_final','tipo_admision'));
    }

    public function adm_act_pdf($fecha_inicial, $fecha_final, $tipo_admision){
    	$empresa = empresa::findOrFail(Auth::user()->empresa_id);
    	if ($tipo_admision == 'T') {
        $admisiones = DB::table('admisiones as a')
                      ->join('users as u', 'a.created_by', 'u.id')
                      ->join('pacientes as p', 'a.paciente_id', 'p.id')
                      ->select('a.id', 'a.admision', 'a.fecha', 'p.expediente_no', 'p.nombre_completo as paciente_nombre', DB::raw('(CASE when a.tipo_admision = "C" then "Consulta" when a.tipo_admision = "P" then "Procedimiento" else "Hospitalizacion" end) as tipo_admision'), DB::raw('(CASE when a.estado = "P" then "Proceso" when a.estado = "C" then "Cerrada" else "no definido" end)as estado_descripcion'), 'u.name as usuario_nombre')
                      ->where('a.empresa_id', Auth::user()->empresa_id)
                      ->where('a.estado', 'P')
                      ->whereBetween('a.fecha',[$fecha_inicial, $fecha_final])
                      ->orderBy('a.admision', 'desc')
                      ->get();
      }else{
        $admisiones = DB::table('admisiones as a')
                      ->join('users as u', 'a.created_by', 'u.id')
                      ->join('pacientes as p', 'a.paciente_id', 'p.id')
                      ->select('a.id', 'a.admision', 'a.fecha', 'p.expediente_no', 'p.nombre_completo as paciente_nombre', DB::raw('(CASE when a.tipo_admision = "C" then "Consulta" when a.tipo_admision = "P" then "Procedimiento" else "Hospitalizacion" end) as tipo_admision'), DB::raw('(CASE when a.estado = "P" then "Proceso" when a.estado = "C" then "Cerrada" else "no definido" end)as estado_descripcion'), 'u.name as usuario_nombre')
                      ->where('a.empresa_id', Auth::user()->empresa_id)
                      ->where('a.tipo_admision', $tipo_admision)
                      ->where('a.estado', 'P')
                      ->whereBetween('a.fecha',[$fecha_inicial, $fecha_final])
                      ->orderBy('a.admision', 'desc')
                      ->get();
      }
      
      	ini_set('memory_limit', '-1');
        $pdf = PDF::loadView('reportes.rpt_admision_activa_pdf', compact('empresa', 'admisiones', 'fecha_inicial', 'fecha_final', 'tipo_admision'));
        $pdf->setPaper('letter','landscape');
        $nombre_informe = 'admisiones_activas.pdf';
        return $pdf->stream($nombre_informe);
    }

    public function adm_act_xls($fecha_inicial, $fecha_final, $tipo_admision){
        if ($tipo_admision == 'T') {
          $admisiones = DB::table('admisiones as a')
                        ->join('users as u', 'a.created_by', 'u.id')
                        ->join('pacientes as p', 'a.paciente_id', 'p.id')
                        ->select('a.id', 'a.admision as admision', 'a.fecha', 'p.expediente_no', 'p.nombre_completo as paciente_nombre', DB::raw('(CASE when a.tipo_admision = "C" then "Consulta" when a.tipo_admision = "P" then "Procedimiento" else "Hospitalizacion" end) as tipo_admision'), DB::raw('(CASE when a.estado = "P" then "Proceso" when a.estado = "C" then "Cerrada" else "no definido" end)as estado_descripcion'), 'u.name as usuario_nombre')
                        ->where('a.empresa_id', Auth::user()->empresa_id)
                        ->where('a.estado', 'P')
                        ->whereBetween('a.fecha',[$fecha_inicial, $fecha_final])
                        ->orderBy('a.admision', 'desc')
                        ->get();
        }else{
          $admisiones = DB::table('admisiones as a')
                        ->join('users as u', 'a.created_by', 'u.id')
                        ->join('pacientes as p', 'a.paciente_id', 'p.id')
                        ->select('a.id', 'a.admision as admision', 'a.fecha', 'p.expediente_no', 'p.nombre_completo as paciente_nombre', DB::raw('(CASE when a.tipo_admision = "C" then "Consulta" when a.tipo_admision = "P" then "Procedimiento" else "Hospitalizacion" end) as tipo_admision'), DB::raw('(CASE when a.estado = "P" then "Proceso" when a.estado = "C" then "Cerrada" else "no definido" end)as estado_descripcion'), 'u.name as usuario_nombre')
                        ->where('a.empresa_id', Auth::user()->empresa_id)
                        ->where('a.tipo_admision', $tipo_admision)
                        ->where('a.estado', 'P')
                        ->whereBetween('a.fecha',[$fecha_inicial, $fecha_final])
                        ->orderBy('a.admision', 'desc')
                        ->get();
        }

        $titulo = 'Admisiones Activas'; //nombre de hoja
        $headings = ['Admision','Fecha','Creada Por','Tipo', 'Expediente', 'Paciente', 'Estado'];
        $array = [];
        foreach($admisiones as $b)
        {
            $a['admision'] = $b->admision;
            $a['Fecha'] = \Carbon\Carbon::parse($b->fecha)->format('d/m/Y');
            $a['Creada Por'] = $b->usuario_nombre;
            $a['Tipo'] = $b->tipo_admision;
            $a['Expediente'] = $b->expediente_no;
            $a['Paciente'] = $b->paciente_nombre;
            $a['Estado'] = $b->estado_descripcion;
            $array[] = $a;
        }
        return Excel::download(new AdmisionesActivasExport($titulo,$headings,$array), 'Admisiones_activas.xlsx');
    }

    public function adm_cons_idx($fecha_inicial, $fecha_final){
        $hoy = Carbon::now()->format('Y-m-d');
        $admisiones = DB::table('admisiones as a')
                      ->join('pacientes as p', 'a.paciente_id', 'p.id')
                      ->join('users as u', 'a.created_by', 'u.id')
                      ->where('a.empresa_id', Auth::user()->empresa_id)
                      ->where('a.tipo_admision', 'C')
                      ->whereBetween('a.fecha', [$fecha_inicial, $fecha_final])
                      ->select('a.admision', 'a.fecha', 'p.nombre_completo as paciente_nombre', 'p.expediente_no', 'u.name as usuario_nombre',DB::raw('(CASE when a.estado = "P" then "Proceso" when a.estado = "C" then "Cerrada" else "no definido" end)as estado_descripcion'))
                      ->get();
        
        return view('reportes.rpt_admision_consulta_index', compact('admisiones','fecha_inicial','fecha_final','hoy'));
    }

    public function adm_cons_pdf($fecha_inicial, $fecha_final){
        $empresa = empresa::findOrFail(Auth::user()->empresa_id);
        $admisiones = DB::table('admisiones as a')
                      ->join('pacientes as p', 'a.paciente_id', 'p.id')
                      ->join('users as u', 'a.created_by', 'u.id')
                      ->where('a.empresa_id', Auth::user()->empresa_id)
                      ->where('a.tipo_admision', 'C')
                      ->whereBetween('a.fecha', [$fecha_inicial, $fecha_final])
                      ->select('a.admision', 'a.fecha', 'p.nombre_completo as paciente_nombre', 'p.expediente_no', 'u.name as usuario_nombre',DB::raw('(CASE when a.estado = "P" then "Proceso" when a.estado = "C" then "Cerrada" else "no definido" end)as estado_descripcion'))
                      ->get();
        
        ini_set('memory_limit', '-1');
        $pdf = PDF::loadView('reportes.rpt_admision_consulta_pdf', compact('empresa', 'admisiones', 'fecha_inicial', 'fecha_final'));
        $pdf->setPaper('letter','landscape');
        $nombre_informe = 'admisiones_activas.pdf';
        return $pdf->stream($nombre_informe);
    }

    public function adm_cons_xls($fecha_inicial, $fecha_final){
        $admisiones = DB::table('admisiones as a')
                      ->join('pacientes as p', 'a.paciente_id', 'p.id')
                      ->join('users as u', 'a.created_by', 'u.id')
                      ->where('a.empresa_id', Auth::user()->empresa_id)
                      ->where('a.tipo_admision', 'C')
                      ->whereBetween('a.fecha', [$fecha_inicial, $fecha_final])
                      ->select('a.admision', 'a.fecha', 'p.nombre_completo as paciente_nombre', 'p.expediente_no', 'u.name as usuario_nombre',DB::raw('(CASE when a.estado = "P" then "Proceso" when a.estado = "C" then "Cerrada" else "no definido" end)as estado_descripcion'))
                      ->get();

        $titulo = 'Consultas por admision'; //nombre de hoja
        $headings = ['Admision','Fecha','Creada Por', 'Expediente', 'Paciente', 'Estado'];
        $array = [];
        foreach($admisiones as $b)
        {
            $a['admision'] = $b->admision;
            $a['Fecha'] = \Carbon\Carbon::parse($b->fecha)->format('d/m/Y');
            $a['Creada Por'] = $b->usuario_nombre;
            $a['Expediente'] = $b->expediente_no;
            $a['Paciente'] = $b->paciente_nombre;
            $a['Estado'] = $b->estado_descripcion;
            $array[] = $a;
        }
        return Excel::download(new AdmisionConsultasExport($titulo,$headings,$array), 'consultas.xlsx');
    }

    public function adm_hosp_idx($fecha_inicial, $fecha_final){
        $hoy = Carbon::now()->format('Y-m-d');
        $admisiones = DB::table('admisiones as a')
                      ->join('pacientes as p', 'a.paciente_id', 'p.id')
                      ->join('users as u', 'a.created_by', 'u.id')
                      ->join('hospitales as h', 'a.hospital_id', 'h.id')
                      ->where('a.empresa_id', Auth::user()->empresa_id)
                      ->where('a.tipo_admision', 'H')
                      ->whereBetween('a.fecha', [$fecha_inicial, $fecha_final])
                      ->select('a.admision', 'a.fecha', 'p.nombre_completo as paciente_nombre', 'p.expediente_no', 'u.name as usuario_nombre', 'h.nombre as hospital_nombre', 'a.fecha_inicio', 'a.fecha_fin',DB::raw('(CASE when a.estado = "P" then "Proceso" when a.estado = "C" then "Cerrada" else "no definido" end)as estado_descripcion'))
                      ->get();
        
        return view('reportes.rpt_admision_hospital_index', compact('admisiones','fecha_inicial','fecha_final','hoy'));
    }

    public function adm_hosp_pdf($fecha_inicial, $fecha_final){
        $empresa = empresa::findOrFail(Auth::user()->empresa_id);
        $admisiones = DB::table('admisiones as a')
                      ->join('pacientes as p', 'a.paciente_id', 'p.id')
                      ->join('users as u', 'a.created_by', 'u.id')
                      ->join('hospitales as h', 'a.hospital_id', 'h.id')
                      ->where('a.empresa_id', Auth::user()->empresa_id)
                      ->where('a.tipo_admision', 'H')
                      ->whereBetween('a.fecha', [$fecha_inicial, $fecha_final])
                      ->select('a.admision', 'a.fecha', 'p.nombre_completo as paciente_nombre', 'p.expediente_no', 'u.name as usuario_nombre', 'h.nombre as hospital_nombre', 'a.fecha_inicio', 'a.fecha_fin',DB::raw('(CASE when a.estado = "P" then "Proceso" when a.estado = "C" then "Cerrada" else "no definido" end)as estado_descripcion'))
                      ->get();
        
        ini_set('memory_limit', '-1');
        $pdf = PDF::loadView('reportes.rpt_admision_hospital_pdf', compact('empresa', 'admisiones', 'fecha_inicial', 'fecha_final'));
        $pdf->setPaper('letter','landscape');
        $nombre_informe = 'hospitalizaciones.pdf';
        return $pdf->stream($nombre_informe);
    }

    public function adm_hosp_xls($fecha_inicial, $fecha_final){
        $admisiones = DB::table('admisiones as a')
                      ->join('pacientes as p', 'a.paciente_id', 'p.id')
                      ->join('users as u', 'a.created_by', 'u.id')
                      ->join('hospitales as h', 'a.hospital_id', 'h.id')
                      ->where('a.empresa_id', Auth::user()->empresa_id)
                      ->where('a.tipo_admision', 'H')
                      ->whereBetween('a.fecha', [$fecha_inicial, $fecha_final])
                      ->select('a.admision', 'a.fecha', 'p.nombre_completo as paciente_nombre', 'p.expediente_no', 'u.name as usuario_nombre', 'h.nombre as hospital_nombre', 'a.fecha_inicio', 'a.fecha_fin',DB::raw('(CASE when a.estado = "P" then "Proceso" when a.estado = "C" then "Cerrada" else "no definido" end)as estado_descripcion'))
                      ->get();

        $titulo = 'Hospitalizaciones'; //nombre de hoja
        $headings = ['Admision','Fecha','Creada Por', 'Expediente', 'Paciente', 'Hospital', 'Ingreso', 'Egreso', 'Estado'];
        $array = [];
        foreach($admisiones as $b)
        {
            $a['admision'] = $b->admision;
            $a['Fecha'] = \Carbon\Carbon::parse($b->fecha)->format('d/m/Y');
            $a['Creada Por'] = $b->usuario_nombre;
            $a['Expediente'] = $b->expediente_no;
            $a['Paciente'] = $b->paciente_nombre;
            $a['Hospital'] = $b->hospital_nombre;
            $a['Ingreso'] = \Carbon\Carbon::parse($b->fecha_inicio)->format('d/m/Y');
            $a['Egreso'] = \Carbon\Carbon::parse($b->fecha_fin)->format('d/m/Y');
            $a['Estado'] = $b->estado_descripcion;
            $array[] = $a;
        }
        return Excel::download(new AdmisionHospitalizacionesExport($titulo,$headings,$array), 'consultas.xlsx');
    }

    public function adm_proc_idx($fecha_inicial, $fecha_final){
        $hoy = Carbon::now()->format('Y-m-d');
        $admisiones = DB::table('admisiones as a')
                      ->join('admision_procedimientos as ap', 'a.id', 'ap.admision_id')
                      ->join('pacientes as p', 'a.paciente_id', 'p.id')
                      ->join('users as u', 'a.created_by', 'u.id')
                      ->join('hospitales as h', 'a.hospital_id', 'h.id')
                      ->join('productos as pr', 'ap.procedimiento_id', 'pr.id')
                      ->where('a.empresa_id', Auth::user()->empresa_id)
                      ->where('a.tipo_admision', 'P')
                      ->whereBetween('a.fecha', [$fecha_inicial, $fecha_final])
                      ->select('a.admision', 'a.fecha', 'p.nombre_completo as paciente_nombre', 'p.expediente_no', 'u.name as usuario_nombre', 'pr.descripcion as procedimiento_descripcion', 'h.nombre as hospital_nombre',DB::raw('(CASE when a.estado = "P" then "Proceso" when a.estado = "C" then "Cerrada" else "no definido" end)as estado_descripcion'))
                      ->get();
        
        return view('reportes.rpt_admision_procedimiento_index', compact('admisiones','fecha_inicial','fecha_final','hoy'));
    }

    public function adm_proc_pdf($fecha_inicial, $fecha_final){
        $empresa = empresa::findOrFail(Auth::user()->empresa_id);
        $admisiones = DB::table('admisiones as a')
                      ->join('admision_procedimientos as ap', 'a.id', 'ap.admision_id')
                      ->join('pacientes as p', 'a.paciente_id', 'p.id')
                      ->join('users as u', 'a.created_by', 'u.id')
                      ->join('hospitales as h', 'a.hospital_id', 'h.id')
                      ->join('productos as pr', 'ap.procedimiento_id', 'pr.id')
                      ->where('a.empresa_id', Auth::user()->empresa_id)
                      ->where('a.tipo_admision', 'P')
                      ->whereBetween('a.fecha', [$fecha_inicial, $fecha_final])
                      ->select('a.admision', 'a.fecha', 'p.nombre_completo as paciente_nombre', 'p.expediente_no', 'u.name as usuario_nombre', 'pr.descripcion as procedimiento_descripcion', 'h.nombre as hospital_nombre',DB::raw('(CASE when a.estado = "P" then "Proceso" when a.estado = "C" then "Cerrada" else "no definido" end)as estado_descripcion'))
                      ->get();
        
        ini_set('memory_limit', '-1');
        $pdf = PDF::loadView('reportes.rpt_admision_procedimiento_pdf', compact('empresa', 'admisiones', 'fecha_inicial', 'fecha_final'));
        $pdf->setPaper('letter','landscape');
        $nombre_informe = 'hospitalizaciones.pdf';
        return $pdf->stream($nombre_informe);
    }

    public function adm_proc_xls($fecha_inicial, $fecha_final){
        $admisiones = DB::table('admisiones as a')
                      ->join('admision_procedimientos as ap', 'a.id', 'ap.admision_id')
                      ->join('pacientes as p', 'a.paciente_id', 'p.id')
                      ->join('users as u', 'a.created_by', 'u.id')
                      ->join('hospitales as h', 'a.hospital_id', 'h.id')
                      ->join('productos as pr', 'ap.procedimiento_id', 'pr.id')
                      ->where('a.empresa_id', Auth::user()->empresa_id)
                      ->where('a.tipo_admision', 'P')
                      ->whereBetween('a.fecha', [$fecha_inicial, $fecha_final])
                      ->select('a.admision', 'a.fecha', 'p.nombre_completo as paciente_nombre', 'p.expediente_no', 'u.name as usuario_nombre', 'pr.descripcion as procedimiento_nombre', 'h.nombre as hospital_nombre',DB::raw('(CASE when a.estado = "P" then "Proceso" when a.estado = "C" then "Cerrada" else "no definido" end)as estado_descripcion'))
                      ->get();

        $titulo = 'Procedimientos'; //nombre de hoja
        $headings = ['Admision','Fecha','Creada Por', 'Expediente', 'Paciente', 'Procedimiento', 'Hospital', 'Estado'];
        $array = [];
        foreach($admisiones as $b)
        {
            $a['admision'] = $b->admision;
            $a['Fecha'] = \Carbon\Carbon::parse($b->fecha)->format('d/m/Y');
            $a['Creada Por'] = $b->usuario_nombre;
            $a['Expediente'] = $b->expediente_no;
            $a['Paciente'] = $b->paciente_nombre;
            $a['Procedimiento'] = $b->procedimiento_nombre;
            $a['Hospital'] = $b->hospital_nombre;
            $a['Estado'] = $b->estado_descripcion;
            $array[] = $a;
        }
        return Excel::download(new AdmisionProcedimientosExport($titulo,$headings,$array), 'consultas.xlsx');
    }

    public function antiguedad_saldos_idx(){
      $facturas = DB::table('vw_antiguedad_saldos as vas')
                  ->where('vas.empresa_id', Auth::user()->empresa_id)
                  ->select('vas.admision', 'vas.cliente_nombre', 'vas.serie', 'vas.correlativo', 'vas.fecha_emision', 'vas.total_documento', 'vas.saldo', 'vas.dias_30', 'vas.dias_mayor_30', 'vas.dias_mayor_60', 'vas.dias_mayor_90', 'vas.dias_mayor_120')
                  ->get();
      return view('reportes.rpt_antiguedad_saldos_index', compact('facturas'));
    }

    public function antiguedad_saldos_pdf(){
      
      $empresa = empresa::findOrFail(Auth::user()->empresa_id);
      
      $facturas = DB::table('vw_antiguedad_saldos as vas')
                  ->where('vas.empresa_id', Auth::user()->empresa_id)
                  ->select('vas.admision', 'vas.cliente_nombre', 'vas.serie', 'vas.correlativo', 'vas.fecha_emision', 'vas.total_documento', 'vas.saldo', 'vas.dias_30', 'vas.dias_mayor_30', 'vas.dias_mayor_60', 'vas.dias_mayor_90', 'vas.dias_mayor_120')
                  ->get();
      
      ini_set('memory_limit', '-1');
      $pdf = PDF::loadView('reportes.rpt_antiguedad_saldos_pdf', compact('empresa', 'facturas'));
      $pdf->setPaper('letter','landscape');
      $nombre_informe = 'antiguedad_saldos.pdf';
      return $pdf->stream($nombre_informe);
    }

    public function antiguedad_saldos_xls(){
      $facturas = DB::table('vw_antiguedad_saldos as vas')
                  ->where('vas.empresa_id', Auth::user()->empresa_id)
                  ->select('vas.admision', 'vas.cliente_nombre', 'vas.serie', 'vas.correlativo', 'vas.fecha_emision', 'vas.total_documento', 'vas.saldo', 'vas.dias_30', 'vas.dias_mayor_30', 'vas.dias_mayor_60', 'vas.dias_mayor_90', 'vas.dias_mayor_120')
                  ->get();

      $titulo = 'Antiguedad de Saldos'; //nombre de hoja
      $headings = ['Admision','Cliente','Documento', 'Fecha', 'Total', 'Saldo', '30 Dias', '60 Dias', '90 Dias', '120 Dias', 'Mas de 120 Dias'];
        $array = [];
        foreach($facturas as $f)
        {
            $a['admision'] = $f->admision;
            $a['cliente']  = $f->cliente_nombre;
            $a['documento'] = $f->serie.'-'.$f->correlativo;
            $a['fecha'] = \Carbon\Carbon::parse($f->fecha_emision)->format('d/m/Y');
            $a['total'] = $f->total_documento;
            $a['saldo'] = $f->saldo;
            $a['dias_30'] = $f->dias_30;
            $a['dias_mayor_30'] = $f->dias_mayor_30;
            $a['dias_mayor_60'] = $f->dias_mayor_60;
            $a['dias_mayor_90'] = $f->dias_mayor_90;
            $a['dias_mayor_120'] = $f->dias_mayor_120;
            $array[] = $a;
        }
        return Excel::download(new AntiguedadSaldosExport($titulo,$headings,$array), 'antiguedad_de_saldos.xlsx');
    }

    public function admisiones_con_saldo_idx(){
      $admisiones = DB::table('vw_admisiones_con_saldo as vacs')
                    ->where('empresa_id', Auth::user()->empresa_id)
                    ->where('saldo','>',0)
                    ->select('vacs.admision', 'vacs.paciente_nombre', 'vacs.fecha', 'vacs.total_cargos', 'vacs.total_facturado', 'vacs.total_pagado', 'vacs.saldo')
                    ->get();
      return view('reportes.rpt_admisiones_con_saldo_idx', compact('admisiones'));
    }

    public function admisiones_con_saldo_pdf(){
      $empresa = empresa::findOrFail(Auth::user()->empresa_id);

      $admisiones = DB::table('vw_admisiones_con_saldo as vacs')
                    ->where('empresa_id', Auth::user()->empresa_id)
                    ->where('saldo','>',0)
                    ->select('vacs.admision', 'vacs.paciente_nombre', 'vacs.fecha', 'vacs.total_cargos', 'vacs.total_facturado', 'vacs.total_pagado', 'vacs.saldo')
                    ->get();

      ini_set('memory_limit', '-1');
      $pdf = PDF::loadView('reportes.rpt_admisiones_con_saldo_pdf', compact('empresa', 'admisiones'));
      $pdf->setPaper('letter','landscape');
      $nombre_informe = 'admisiones_con_saldo.pdf';
      return $pdf->stream($nombre_informe);
    }

    public function admisiones_con_saldo_xls(){
      $admisiones = DB::table('vw_admisiones_con_saldo as vacs')
                    ->where('empresa_id', Auth::user()->empresa_id)
                    ->where('saldo','>',0)
                    ->select('vacs.admision', 'vacs.paciente_nombre', 'vacs.fecha', 'vacs.total_cargos', 'vacs.total_facturado', 'vacs.total_pagado', 'vacs.saldo')
                    ->get();

      $titulo = 'Antiguedad de Saldos'; //nombre de hoja
      $headings = ['Admision', 'Paciente', 'Fecha', 'Total Cargps', 'Total Facturado', 'Saldo'];
      $array = [];
      foreach($admisiones as $f)
      {
          $a['admision'] = $f->admision;
          $a['paciente']  = $f->paciente_nombre;
          $a['fecha'] = \Carbon\Carbon::parse($f->fecha)->format('d/m/Y');
          $a['total_cargo'] = $f->total_cargos;
          $a['total_facturado'] = $f->total_facturado;
          $a['saldo'] = $f->saldo;
          $array[] = $a;
      }
      return Excel::download(new AdmisionesConSaldoExport($titulo,$headings,$array), 'admisiones_con_saldo.xlsx');
    }

    function admisiones_por_fecha_idx($tipo_admision, $fecha_inicial, $fecha_final){
      $hoy = Carbon::now()->format('Y-m-d');
      if ($tipo_admision == 'T') {
        $admisiones = DB::table('admisiones as a')
                      ->join('pacientes as p', 'a.paciente_id', 'p.id')
                      ->join('users as u', 'a.created_by', 'u.id')
                      ->leftjoin('vw_facturas_por_admision as vfpa', 'a.id', 'vfpa.admision_id')
                      ->where('a.empresa_id', Auth::user()->empresa_id)
                      ->whereDate('a.fecha', '>=', $fecha_inicial)
                      ->whereDate('a.fecha', '<=', $fecha_final)
                      ->select('a.id as admision_id', 'a.admision', 'a.fecha_inicio', 'a.fecha_fin', 'p.nombre_completo as paciente_nombre', 'u.name as usuario_nombre', 'vfpa.facturas', 'a.fecha', 'a.tipo_admision')
                      ->get();
      }else{
        $admisiones = DB::table('admisiones as a')
                      ->join('pacientes as p', 'a.paciente_id', 'p.id')
                      ->join('users as u', 'a.created_by', 'u.id')
                      ->leftjoin('vw_facturas_por_admision as vfpa', 'a.id', 'vfpa.admision_id')
                      ->where('a.empresa_id', Auth::user()->empresa_id)
                      ->where('a.tipo_admision', $tipo_admision)
                      ->whereDate('a.fecha', '>=', $fecha_inicial)
                      ->whereDate('a.fecha', '<=', $fecha_final)
                      ->select('a.id as admision_id', 'a.admision', 'a.fecha_inicio', 'a.fecha_fin', 'p.nombre_completo as paciente_nombre', 'u.name as usuario_nombre', 'vfpa.facturas', 'a.fecha', 'a.tipo_admision')
                      ->get();
      }

      return view('reportes.rpt_admisiones_por_fecha_idx', compact('admisiones', 'hoy', 'tipo_admision', 'fecha_inicial', 'fecha_final'));
    }

    public function admisiones_por_fecha_pdf($tipo_admision, $fecha_inicial, $fecha_final){
      $empresa = empresa::findOrFail(Auth::user()->empresa_id);
      if ($tipo_admision == 'T') {
        $admisiones = DB::table('admisiones as a')
                      ->join('pacientes as p', 'a.paciente_id', 'p.id')
                      ->join('users as u', 'a.created_by', 'u.id')
                      ->leftjoin('vw_facturas_por_admision as vfpa', 'a.id', 'vfpa.admision_id')
                      ->where('a.empresa_id', Auth::user()->empresa_id)
                      ->whereDate('a.fecha', '>=', $fecha_inicial)
                      ->whereDate('a.fecha', '<=', $fecha_final)
                      ->select('a.id as admision_id', 'a.admision', 'a.fecha_inicio', 'a.fecha_fin', 'p.nombre_completo as paciente_nombre', 'u.name as usuario_nombre', 'vfpa.facturas', 'a.fecha', 'a.tipo_admision')
                      ->get();
      }else{
        $admisiones = DB::table('admisiones as a')
                      ->join('pacientes as p', 'a.paciente_id', 'p.id')
                      ->join('users as u', 'a.created_by', 'u.id')
                      ->leftjoin('vw_facturas_por_admision as vfpa', 'a.id', 'vfpa.admision_id')
                      ->where('a.empresa_id', Auth::user()->empresa_id)
                      ->where('a.tipo_admision', $tipo_admision)
                      ->whereDate('a.fecha', '>=', $fecha_inicial)
                      ->whereDate('a.fecha', '<=', $fecha_final)
                      ->select('a.id as admision_id', 'a.admision', 'a.fecha_inicio', 'a.fecha_fin', 'p.nombre_completo as paciente_nombre', 'u.name as usuario_nombre', 'vfpa.facturas', 'a.fecha', 'a.tipo_admision')
                      ->get();
      }
      switch ($tipo_admision) {
        case 'C':
          $tipo_descripcion = 'Consultas';
          break;
        case 'P':
          $tipo_descripcion = 'Procedimientos';
          break;
        case 'H':
          $tipo_descripcion = 'Hospitalizaciones';
          break;
        default:
          $tipo_descripcion = 'Admisiones';
          break;
      }

      ini_set('memory_limit', '-1');
      $pdf = PDF::loadView('reportes.rpt_admisiones_por_fecha_pdf', compact('empresa', 'admisiones', 'tipo_admision', 'tipo_descripcion', 'fecha_inicial', 'fecha_final'));
      $pdf->setPaper('letter','landscape');
      $nombre_informe = 'admisiones_por_fecha.pdf';
      return $pdf->stream($nombre_informe);
    }

    public function admisiones_por_fecha_xls($tipo_admision, $fecha_inicial, $fecha_final){
      if ($tipo_admision == 'T') {
        $admisiones = DB::table('admisiones as a')
                      ->join('pacientes as p', 'a.paciente_id', 'p.id')
                      ->join('users as u', 'a.created_by', 'u.id')
                      ->leftjoin('vw_facturas_por_admision as vfpa', 'a.id', 'vfpa.admision_id')
                      ->where('a.empresa_id', Auth::user()->empresa_id)
                      ->whereDate('a.fecha', '>=', $fecha_inicial)
                      ->whereDate('a.fecha', '<=', $fecha_final)
                      ->select('a.id as admision_id', 'a.admision', 'a.fecha_inicio', 'a.fecha_fin', 'p.nombre_completo as paciente_nombre', 'u.name as usuario_nombre', 'vfpa.facturas', 'a.fecha', 'a.tipo_admision')
                      ->get();
      }else{
        $admisiones = DB::table('admisiones as a')
                      ->join('pacientes as p', 'a.paciente_id', 'p.id')
                      ->join('users as u', 'a.created_by', 'u.id')
                      ->leftjoin('vw_facturas_por_admision as vfpa', 'a.id', 'vfpa.admision_id')
                      ->where('a.empresa_id', Auth::user()->empresa_id)
                      ->where('a.tipo_admision', $tipo_admision)
                      ->whereDate('a.fecha', '>=', $fecha_inicial)
                      ->whereDate('a.fecha', '<=', $fecha_final)
                      ->select('a.id as admision_id', 'a.admision', 'a.fecha_inicio', 'a.fecha_fin', 'p.nombre_completo as paciente_nombre', 'u.name as usuario_nombre', 'vfpa.facturas', 'a.fecha', 'a.tipo_admision')
                      ->get();
      }
      switch ($tipo_admision) {
        case 'C':
          $tipo_descripcion = 'Consultas';
          break;
        case 'P':
          $tipo_descripcion = 'Procedimientos';
          break;
        case 'H':
          $tipo_descripcion = 'Hospitalizaciones';
          break;
        default:
          $tipo_descripcion = 'Admisiones';
          break;
      }

      $titulo = $tipo_descripcion.' por Fecha'; //nombre de hoja
      $headings = ['Admision', 'Fecha', 'Creado por', 'Tipo', 'Entrada', 'Salida', 'Paciente', 'Facturas'];
      $array = [];
      foreach($admisiones as $f)
      {
          $a['admision'] = $f->admision;
          $a['fecha'] = \Carbon\Carbon::parse($f->fecha)->format('d/m/Y');
          $a['creado_por'] = $f->usuario_nombre;
          if ($f->tipo_admision == 'C') {
            $a['Tipo'] = 'Consulta';
          }
          if ($f->tipo_admision == 'P') {
            $a['Tipo'] = 'Procedimiento';
          }
          if ($f->tipo_admision == 'H') {
            $a['Tipo'] = 'Hospitalización';
          }
          $a['entrada'] = \Carbon\Carbon::parse($f->fecha_inicio)->format('d/m/Y');
          $a['salida'] = \Carbon\Carbon::parse($f->fecha_fin)->format('d/m/Y');
          $a['paciente'] = $f->paciente_nombre;
          $a['facturas'] = $f->facturas;
          $array[] = $a;
      }
      return Excel::download(new AdmisionesPorFechaExport($titulo,$headings,$array), $tipo_descripcion.'_por_fecha.xlsx');
    }

    public function arqueo_factura_idx($caja_id, $fecha_inicial, $fecha_final){
      $hoy = Carbon::now()->format('Y-m-d');
      $cajas = Caja::where('empresa_id', Auth::user()->empresa_id)->get();
      if ($caja_id == 0) {
        $listado = DB::table('vw_arqueo_facturas as vaf')
                    ->where('vaf.empresa_id', Auth::user()->empresa_id)
                    ->whereBetween('vaf.fecha_emision', [$fecha_inicial, $fecha_final])
                    ->select('vaf.nombre_maquina', 'vaf.tipodocumento_descripcion', 'vaf.serie', 'vaf.correlativo', 'vaf.fecha_emision', 'vaf.nombre', 'vaf.subtotal', 'vaf.descuento', 'vaf.recargo', 'vaf.precio_neto', 'vaf.total_pagado', 'vaf.saldo')
                    ->get();
      }else{
        $listado = DB::table('vw_arqueo_facturas as vaf')
                    ->where('vaf.empresa_id', Auth::user()->empresa_id)
                    ->where('vaf.caja_id', $caja_id)
                    ->whereBetween('vaf.fecha_emision', [$fecha_inicial, $fecha_final])
                    ->select('vaf.nombre_maquina', 'vaf.tipodocumento_descripcion', 'vaf.serie', 'vaf.correlativo', 'vaf.fecha_emision', 'vaf.nombre', 'vaf.subtotal', 'vaf.descuento', 'vaf.recargo', 'vaf.precio_neto', 'vaf.total_pagado', 'vaf.saldo')
                    ->get();
      }
      return view('reportes.rpt_arqueo_facturas_idx', compact('listado','hoy', 'caja_id', 'fecha_inicial', 'fecha_final', 'cajas'));
    }

    public function arqueo_factura_pdf($caja_id, $fecha_inicial, $fecha_final){
      $empresa = empresa::findOrFail(Auth::user()->empresa_id);
      if ($caja_id == 0) {
        $listado = DB::table('vw_arqueo_facturas as vaf')
                    ->where('vaf.empresa_id', Auth::user()->empresa_id)
                    ->whereBetween('vaf.fecha_emision', [$fecha_inicial, $fecha_final])
                    ->select('vaf.nombre_maquina', 'vaf.tipodocumento_descripcion', 'vaf.serie', 'vaf.correlativo', 'vaf.fecha_emision', 'vaf.nombre', 'vaf.subtotal', 'vaf.descuento', 'vaf.recargo', 'vaf.precio_neto', 'vaf.total_pagado', 'vaf.saldo')
                    ->get();
      }else{
        $listado = DB::table('vw_arqueo_facturas as vaf')
                    ->where('vaf.empresa_id', Auth::user()->empresa_id)
                    ->where('vaf.caja_id', $caja_id)
                    ->whereBetween('vaf.fecha_emision', [$fecha_inicial, $fecha_final])
                    ->select('vaf.nombre_maquina', 'vaf.tipodocumento_descripcion', 'vaf.serie', 'vaf.correlativo', 'vaf.fecha_emision', 'vaf.nombre', 'vaf.subtotal', 'vaf.descuento', 'vaf.recargo', 'vaf.precio_neto', 'vaf.total_pagado', 'vaf.saldo')
                    ->get();
      }
      ini_set('memory_limit', '-1');
      $pdf = PDF::loadView('reportes.rpt_arqueo_facturas_pdf', compact('empresa', 'listado', 'caja_id', 'fecha_inicial', 'fecha_final'));
      $pdf->setPaper('letter','landscape');
      $nombre_informe = 'arqueo_facturas.pdf';
      return $pdf->stream($nombre_informe);
    }

    public function arqueo_factura_xls($caja_id, $fecha_inicial, $fecha_final){
      if ($caja_id == 0) {
        $listado = DB::table('vw_arqueo_facturas as vaf')
                    ->where('vaf.empresa_id', Auth::user()->empresa_id)
                    ->whereBetween('vaf.fecha_emision', [$fecha_inicial, $fecha_final])
                    ->select('vaf.nombre_maquina', 'vaf.tipodocumento_descripcion', 'vaf.serie', 'vaf.correlativo', 'vaf.fecha_emision', 'vaf.nombre', 'vaf.subtotal', 'vaf.descuento', 'vaf.recargo', 'vaf.precio_neto', 'vaf.total_pagado', 'vaf.saldo')
                    ->get();
      }else{
        $listado = DB::table('vw_arqueo_facturas as vaf')
                    ->where('vaf.empresa_id', Auth::user()->empresa_id)
                    ->where('vaf.caja_id', $caja_id)
                    ->whereBetween('vaf.fecha_emision', [$fecha_inicial, $fecha_final])
                    ->select('vaf.nombre_maquina', 'vaf.tipodocumento_descripcion', 'vaf.serie', 'vaf.correlativo', 'vaf.fecha_emision', 'vaf.nombre', 'vaf.subtotal', 'vaf.descuento', 'vaf.recargo', 'vaf.precio_neto', 'vaf.total_pagado', 'vaf.saldo')
                    ->get();
      }
      $titulo = 'Arqueo de Facturas'; //nombre de hoja
      $headings = ['Caja', 'Tipo Documento', 'Documento', 'Fecha', 'Nombre', 'Sub Total', 'Descuento', 'Recargo', 'Total', 'Saldo'];
      $array = [];
      foreach($listado as $l)
      {
          $a['nombre_maquina'] = $l->nombre_maquina;
          $a['tipodocumento_descripcion']  = $l->tipodocumento_descripcion;
          $a['documento'] = $l->serie.'-'.$l->correlativo;
          $a['fecha'] = \Carbon\Carbon::parse($l->fecha_emision)->format('d/m/Y');
          $a['nombre'] = $l->nombre;
          $a['subtotal'] = $l->subtotal;
          $a['descuento'] = $l->descuento;
          $a['recargo'] = $l->recargo;
          $a['total'] = $l->precio_neto;
          $a['saldo'] = $l->saldo;
          $array[] = $a;
      }
      return Excel::download(new variosExport($titulo,$headings,$array), 'arqueo_facturas.xlsx');
    }

    public function arqueo_recibo_idx($caja_id, $fecha_inicial, $fecha_final){
      $hoy = Carbon::now()->format('Y-m-d');
      $cajas = Caja::where('empresa_id', Auth::user()->empresa_id)->get();
      if ($caja_id == 0) {
        $listado = DB::table('maestro_pagos as mp')
                   ->join('detalle_pagos as dp', 'mp.id', 'dp.maestro_pago_id')
                   ->join('pacientes as p', 'mp.paciente_id', 'p.id')
                   ->join('pago_documentos as pd', 'mp.id', 'pd.maestro_pago_id')
                   ->join('admisiones as a', 'pd.admision_id', 'a.id')
                   ->where('mp.empresa_id', Auth::user()->empresa_id)
                   ->groupby('mp.serie', 'mp.correlativo', 'p.nombre_completo', 'a.tipo_admision', 'a.admision')
                   ->select('mp.serie', 'mp.correlativo', 'p.nombre_completo', 'a.tipo_admision', 'a.admision',
                            DB::raw('sum(dp.monto) as total'),
                            DB::raw('sum(case when dp.forma_pago = "E" then dp.monto else 0 end) efectivo'),
                            DB::raw('sum(case when dp.forma_pago = "B" then dp.monto else 0 end) cheque'),
                            DB::raw('sum(case when dp.forma_pago = "T" then dp.monto else 0 end) tarjeta')
                          )
                   ->get();
      }else{
        $listado = DB::table('maestro_pagos as mp')
                   ->join('detalle_pagos as dp', 'mp.id', 'dp.maestro_pago_id')
                   ->join('pacientes as p', 'mp.paciente_id', 'p.id')
                   ->join('pago_documentos as pd', 'mp.id', 'pd.maestro_pago_id')
                   ->join('admisiones as a', 'pd.admision_id', 'a.id')
                   ->where('mp.empresa_id', Auth::user()->empresa_id)
                   ->groupby('mp.serie', 'mp.correlativo', 'p.nombre_completo', 'a.tipo_admision', 'a.admision')
                   ->select('mp.serie', 'mp.correlativo', 'p.nombre_completo', 'a.tipo_admision', 'a.admision',
                            DB::raw('sum(dp.monto) as total'),
                            DB::raw('sum(case when dp.forma_pago = "E" then dp.monto else 0 end) efectivo'),
                            DB::raw('sum(case when dp.forma_pago = "B" then dp.monto else 0 end) cheque'),
                            DB::raw('sum(case when dp.forma_pago = "T" then dp.monto else 0 end) tarjeta'))
                   ->get();
      }

      return view('reportes.rpt_arqueo_recibos_idx', compact('listado','hoy', 'caja_id', 'fecha_inicial', 'fecha_final', 'cajas'));
    }

    public function arqueo_cheques_idx($caja_id, $fecha_inicial, $fecha_final){
      $hoy = Carbon::now()->format('Y-m-d');
      $cajas = Caja::where('empresa_id', Auth::user()->empresa_id)->get();
      if ($caja_id == 0) {
        $listado = DB::table('maestro_pagos as mp')
                   ->join('detalle_pagos as dp', 'mp.id', 'dp.maestro_pago_id')
                   ->join('bancos as b', 'dp.banco_id', 'b.id')
                   ->select('mp.serie', 'mp.correlativo', 'mp.fecha_emision', 'b.nombre as banco_nombre', 'dp.cuenta_no', 'dp.documento_no', 'dp.autoriza_no', 'dp.monto')
                   ->where('mp.empresa_id', Auth::user()->empresa_id)
                   ->where('dp.forma_pago', 'B')
                   ->whereBetween('mp.fecha_emision',[$fecha_inicial, $fecha_final])
                   ->get();
      }else{
        $listado = DB::table('maestro_pagos as mp')
                   ->join('detalle_pagos as dp', 'mp.id', 'dp.maestro_pago_id')
                   ->join('bancos as b', 'dp.banco_id', 'b.id')
                   ->select('mp.serie', 'mp.correlativo', 'mp.fecha_emision', 'b.nombre as banco_nombre', 'dp.cuenta_no', 'dp.documento_no', 'dp.autoriza_no', 'dp.monto')
                   ->where('mp.empresa_id', Auth::user()->empresa_id)
                   ->where('mp.caja_id', $caja_id)
                   ->where('dp.forma_pago', 'B')
                   ->whereBetween('mp.fecha_emision',[$fecha_inicial, $fecha_final])
                   ->get();
      }

      $total = 0;

      foreach ($listado as $l) {
        $total += $l->monto;
      }

      return view('reportes.rpt_arqueo_cheques_idx', compact('listado', 'hoy', 'caja_id', 'fecha_inicial', 'fecha_final', 'cajas', 'total'));
    }

    public function arqueo_cheques_pdf($caja_id, $fecha_inicial, $fecha_final){
      $empresa = empresa::findOrFail(Auth::user()->empresa_id);
      if ($caja_id == 0) {
        $listado = DB::table('maestro_pagos as mp')
                   ->join('detalle_pagos as dp', 'mp.id', 'dp.maestro_pago_id')
                   ->join('bancos as b', 'dp.banco_id', 'b.id')
                   ->select('mp.serie', 'mp.correlativo', 'mp.fecha_emision', 'b.nombre as banco_nombre', 'dp.cuenta_no', 'dp.documento_no', 'dp.autoriza_no', 'dp.monto')
                   ->where('mp.empresa_id', Auth::user()->empresa_id)
                   ->where('dp.forma_pago', 'B')
                   ->whereBetween('mp.fecha_emision',[$fecha_inicial, $fecha_final])
                   ->get();
      }else{
        $listado = DB::table('maestro_pagos as mp')
                   ->join('detalle_pagos as dp', 'mp.id', 'dp.maestro_pago_id')
                   ->join('bancos as b', 'dp.banco_id', 'b.id')
                   ->select('mp.serie', 'mp.correlativo', 'mp.fecha_emision', 'b.nombre as banco_nombre', 'dp.cuenta_no', 'dp.documento_no', 'dp.autoriza_no', 'dp.monto')
                   ->where('mp.empresa_id', Auth::user()->empresa_id)
                   ->where('mp.caja_id', $caja_id)
                   ->where('dp.forma_pago', 'B')
                   ->whereBetween('mp.fecha_emision',[$fecha_inicial, $fecha_final])
                   ->get();
      }

      $total = 0;

      foreach ($listado as $l) {
        $total += $l->monto;
      }
      ini_set('memory_limit', '-1');
      $pdf = PDF::loadView('reportes.rpt_arqueo_cheques_pdf', compact('empresa', 'listado', 'caja_id', 'fecha_inicial', 'fecha_final', 'total'));
      $pdf->setPaper('letter','portrait');
      $nombre_informe = 'arqueo_cheques.pdf';
      return $pdf->stream($nombre_informe);            
    }

    public function arqueo_cheques_xls($caja_id, $fecha_inicial, $fecha_final){
      if ($caja_id == 0) {
        $listado = DB::table('maestro_pagos as mp')
                   ->join('detalle_pagos as dp', 'mp.id', 'dp.maestro_pago_id')
                   ->join('bancos as b', 'dp.banco_id', 'b.id')
                   ->select('mp.serie', 'mp.correlativo', 'mp.fecha_emision', 'b.nombre as banco_nombre', 'dp.cuenta_no', 'dp.documento_no', 'dp.autoriza_no', 'dp.monto')
                   ->where('mp.empresa_id', Auth::user()->empresa_id)
                   ->where('dp.forma_pago', 'B')
                   ->whereBetween('mp.fecha_emision',[$fecha_inicial, $fecha_final])
                   ->get();
      }else{
        $listado = DB::table('maestro_pagos as mp')
                   ->join('detalle_pagos as dp', 'mp.id', 'dp.maestro_pago_id')
                   ->join('bancos as b', 'dp.banco_id', 'b.id')
                   ->select('mp.serie', 'mp.correlativo', 'mp.fecha_emision', 'b.nombre as banco_nombre', 'dp.cuenta_no', 'dp.documento_no', 'dp.autoriza_no', 'dp.monto')
                   ->where('mp.empresa_id', Auth::user()->empresa_id)
                   ->where('mp.caja_id', $caja_id)
                   ->where('dp.forma_pago', 'B')
                   ->whereBetween('mp.fecha_emision',[$fecha_inicial, $fecha_final])
                   ->get();
      }

      $titulo = 'Arqueo de Cheques'; //nombre de hoja
      $headings = ['Recibo', 'Fecha', 'Banco', 'No. Cuenta', 'No. Documento', 'No. Autorizacion', 'Monto'];
      $array = [];
      foreach($listado as $l)
      {
          $a['recibo'] = $l->serie.'-'.$l->correlativo;
          $a['fecha'] = \Carbon\Carbon::parse($l->fecha_emision)->format('d/m/Y');
          $a['banco'] = $l->banco_nombre;
          $a['no_cuenta'] = $l->cuenta_no;
          $a['no_documento'] = $l->documento_no;
          $a['no_autoriza'] = $l->autoriza_no;
          $a['monto'] = $l->monto;
          $array[] = $a;
      }
      return Excel::download(new variosExport($titulo,$headings,$array), 'arqueo_facturas.xlsx');
    }

    public function arqueo_tarjetas_idx($caja_id, $fecha_inicial, $fecha_final){
      $hoy = Carbon::now()->format('Y-m-d');
      $cajas = Caja::where('empresa_id', Auth::user()->empresa_id)->get();
      if ($caja_id == 0) {
        $listado = DB::table('maestro_pagos as mp')
                   ->join('detalle_pagos as dp', 'mp.id', 'dp.maestro_pago_id')
                   ->join('bancos as b', 'dp.banco_id', 'b.id')
                   ->select('mp.serie', 'mp.correlativo', 'mp.fecha_emision', 'b.nombre as banco_nombre', 'dp.cuenta_no', 'dp.documento_no', 'dp.autoriza_no', 'dp.monto')
                   ->where('mp.empresa_id', Auth::user()->empresa_id)
                   ->where('dp.forma_pago', 'T')
                   ->whereBetween('mp.fecha_emision',[$fecha_inicial, $fecha_final])
                   ->get();
      }else{
        $listado = DB::table('maestro_pagos as mp')
                   ->join('detalle_pagos as dp', 'mp.id', 'dp.maestro_pago_id')
                   ->join('bancos as b', 'dp.banco_id', 'b.id')
                   ->select('mp.serie', 'mp.correlativo', 'mp.fecha_emision', 'b.nombre as banco_nombre', 'dp.cuenta_no', 'dp.documento_no', 'dp.autoriza_no', 'dp.monto')
                   ->where('mp.empresa_id', Auth::user()->empresa_id)
                   ->where('mp.caja_id', $caja_id)
                   ->where('dp.forma_pago', 'T')
                   ->whereBetween('mp.fecha_emision',[$fecha_inicial, $fecha_final])
                   ->get();
      }

      $total = 0;

      foreach ($listado as $l) {
        $total += $l->monto;
      }

      return view('reportes.rpt_arqueo_tarjeta_idx', compact('listado', 'hoy', 'caja_id', 'fecha_inicial', 'fecha_final', 'cajas', 'total'));
    }

    public function arqueo_tarjetas_pdf($caja_id, $fecha_inicial, $fecha_final){
      $empresa = empresa::findOrFail(Auth::user()->empresa_id);
      if ($caja_id == 0) {
        $listado = DB::table('maestro_pagos as mp')
                   ->join('detalle_pagos as dp', 'mp.id', 'dp.maestro_pago_id')
                   ->join('bancos as b', 'dp.banco_id', 'b.id')
                   ->select('mp.serie', 'mp.correlativo', 'mp.fecha_emision', 'b.nombre as banco_nombre', 'dp.cuenta_no', 'dp.documento_no', 'dp.autoriza_no', 'dp.monto')
                   ->where('mp.empresa_id', Auth::user()->empresa_id)
                   ->where('dp.forma_pago', 'T')
                   ->whereBetween('mp.fecha_emision',[$fecha_inicial, $fecha_final])
                   ->get();
      }else{
        $listado = DB::table('maestro_pagos as mp')
                   ->join('detalle_pagos as dp', 'mp.id', 'dp.maestro_pago_id')
                   ->join('bancos as b', 'dp.banco_id', 'b.id')
                   ->select('mp.serie', 'mp.correlativo', 'mp.fecha_emision', 'b.nombre as banco_nombre', 'dp.cuenta_no', 'dp.documento_no', 'dp.autoriza_no', 'dp.monto')
                   ->where('mp.empresa_id', Auth::user()->empresa_id)
                   ->where('mp.caja_id', $caja_id)
                   ->where('dp.forma_pago', 'T')
                   ->whereBetween('mp.fecha_emision',[$fecha_inicial, $fecha_final])
                   ->get();
      }

      $total = 0;

      foreach ($listado as $l) {
        $total += $l->monto;
      }
      ini_set('memory_limit', '-1');
      $pdf = PDF::loadView('reportes.rpt_arqueo_tarjetas_pdf', compact('empresa', 'listado', 'caja_id', 'fecha_inicial', 'fecha_final', 'total'));
      $pdf->setPaper('letter','portrait');
      $nombre_informe = 'arqueo_tarjetas.pdf';
      return $pdf->stream($nombre_informe);            
    }

    public function arqueo_tarjetas_xls($caja_id, $fecha_inicial, $fecha_final){
      if ($caja_id == 0) {
        $listado = DB::table('maestro_pagos as mp')
                   ->join('detalle_pagos as dp', 'mp.id', 'dp.maestro_pago_id')
                   ->join('bancos as b', 'dp.banco_id', 'b.id')
                   ->select('mp.serie', 'mp.correlativo', 'mp.fecha_emision', 'b.nombre as banco_nombre', 'dp.cuenta_no', 'dp.documento_no', 'dp.autoriza_no', 'dp.monto')
                   ->where('mp.empresa_id', Auth::user()->empresa_id)
                   ->where('dp.forma_pago', 'T')
                   ->whereBetween('mp.fecha_emision',[$fecha_inicial, $fecha_final])
                   ->get();
      }else{
        $listado = DB::table('maestro_pagos as mp')
                   ->join('detalle_pagos as dp', 'mp.id', 'dp.maestro_pago_id')
                   ->join('bancos as b', 'dp.banco_id', 'b.id')
                   ->select('mp.serie', 'mp.correlativo', 'mp.fecha_emision', 'b.nombre as banco_nombre', 'dp.cuenta_no', 'dp.documento_no', 'dp.autoriza_no', 'dp.monto')
                   ->where('mp.empresa_id', Auth::user()->empresa_id)
                   ->where('mp.caja_id', $caja_id)
                   ->where('dp.forma_pago', 'T')
                   ->whereBetween('mp.fecha_emision',[$fecha_inicial, $fecha_final])
                   ->get();
      }

      $titulo = 'Arqueo de Cheques'; //nombre de hoja
      $headings = ['Recibo', 'Fecha', 'Banco', 'No. Cuenta', 'No. Documento', 'No. Autorizacion', 'Monto'];
      $array = [];
      foreach($listado as $l)
      {
          $a['recibo'] = $l->serie.'-'.$l->correlativo;
          $a['fecha'] = \Carbon\Carbon::parse($l->fecha_emision)->format('d/m/Y');
          $a['banco'] = $l->banco_nombre;
          $a['no_cuenta'] = $l->cuenta_no;
          $a['no_documento'] = $l->documento_no;
          $a['no_autoriza'] = $l->autoriza_no;
          $a['monto'] = $l->monto;
          $array[] = $a;
      }
      return Excel::download(new variosExport($titulo,$headings,$array), 'arqueo_tarjetas.xlsx');
    }

    public function rpt_cargos_pdf($admision_id){
      $empresa = empresa::findOrFail(Auth::user()->empresa_id);
      $encabezado = DB::table('admisiones as a')
                    ->join('medicos as m', 'a.medico_id', 'm.id')
                    ->join('pacientes as p', 'a.paciente_id', 'p.id')
                    ->where('a.empresa_id', Auth::user()->empresa_id)
                    ->where('a.id', $admision_id)
                    ->select('a.serie', 'a.admision', 'a.fecha', 'm.nombre_completo as medico_nombre', 'p.expediente_no', 'p.nombre_completo as paciente_nombre', 'p.telefonos', 'p.celular', 'a.fecha_inicio', 'a.fecha_fin')
                    ->first();
      $detalle = DB::table('admision_cargos as ac')
                 ->where('ac.admision_id', $admision_id)
                 ->select('ac.created_at', 'ac.cantidad', 'ac.descripcion', 'ac.precio_total')
                 ->get();
      $total = admision_cargo::where('admision_id', $admision_id)->sum('precio_total');

      ini_set('memory_limit', '-1');
      $pdf = PDF::loadView('reportes.rpt_cargos_pdf', compact('empresa', 'encabezado', 'detalle', 'total'));
      $pdf->setPaper('letter','portrait');
      $nombre_informe = 'admision_'.$admision_id.'_cargos.pdf';
      return $pdf->stream($nombre_informe);
    }

    public function rpt_estado_cuenta_pdf($admision_id){
      $empresa = empresa::findOrFail(Auth::user()->empresa_id);
    }

    public function rpt_cargos_sin_factura_idx(){
      $hoy = Carbon::now()->format('Y-m-d');
      $listado = DB::table('admisiones as a')
                 ->join('pacientes as p', 'a.paciente_id', 'p.id')
                 ->join('admision_cargos as ac', 'a.id', 'ac.admision_id')
                 ->join('admision_cargo_detalles as acd', 'ac.id', 'acd.admision_cargo_id')
                 ->join('users as u', 'u.id', 'acd.created_by')
                 ->groupby('a.id', 'a.admision', 'a.fecha', 'p.nombre_completo', 'acd.created_at', 'ac.precio_total', 'u.name')
                 ->select('a.id', 'a.admision', 'a.fecha', 'p.nombre_completo as paciente_nombre', 'acd.created_at as fecha_cargo', DB::raw('SUM(CASE when acd.facturar_a = "C" then acd.valor else 0 end) as cliente'), DB::raw('SUM(CASE when acd.facturar_a = "A" then acd.valor else 0 end) as aseguradora'), DB::raw('SUM(ac.precio_total) as precio_total'), 'u.name as usuario_nombre')
                 ->groupby('a.id', 'a.admision', 'a.fecha', 'p.nombre_completo', 'acd.created_at', 'u.name')
                 ->where('a.empresa_id', Auth::user()->empresa_id)
                 ->whereNotExists(function ($query)
                  {
                    $query->select(DB::raw(1))
                            ->from('detalle_documentos as dd')
                            ->whereRaw('dd.admision_cargo_detalle_id = acd.id');
                  })
                 ->get();

      //dd($listado);

      return view('reportes.rpt_cargos_sin_facturar_index', compact('hoy','listado'));
    }

    public function rpt_cargos_sin_factura_pdf(){
      $empresa = empresa::findOrFail(Auth::user()->empresa_id);
      $listado = DB::table('admisiones as a')
                 ->join('pacientes as p', 'a.paciente_id', 'p.id')
                 ->join('admision_cargos as ac', 'a.id', 'ac.admision_id')
                 ->join('admision_cargo_detalles as acd', 'ac.id', 'acd.admision_cargo_id')
                 ->join('users as u', 'u.id', 'acd.created_by')
                 ->groupby('a.id', 'a.admision', 'a.fecha', 'p.nombre_completo', 'acd.created_at', 'ac.precio_total', 'u.name')
                 ->select('a.id', 'a.admision', 'a.fecha', 'p.nombre_completo as paciente_nombre', 'acd.created_at as fecha_cargo', DB::raw('SUM(CASE when acd.facturar_a = "C" then acd.valor else 0 end) as cliente'), DB::raw('SUM(CASE when acd.facturar_a = "A" then acd.valor else 0 end) as aseguradora'), 'ac.precio_total', 'u.name as usuario_nombre')
                 ->where('a.empresa_id', Auth::user()->empresa_id)
                 ->whereNotExists(function ($query)
                  {
                    $query->select(DB::raw(1))
                            ->from('detalle_documentos as dd')
                            ->whereRaw('dd.admision_cargo_detalle_id = acd.id');
                  })
                 ->get();
      $total_cliente     = 0;
      $total_aseguradora = 0;
      $total_cargos      = 0;
      foreach ($listado as $l) {
        $total_cliente     += $l->cliente;
        $total_aseguradora += $l->aseguradora;
        $total_cargos      += $l->precio_total;
      }

      ini_set('memory_limit', '-1');
      $pdf = PDF::loadView('reportes.rpt_cargos_sin_facturar_pdf', compact('empresa', 'listado', 'total_cliente', 'total_aseguradora', 'total_cargos'));
      $pdf->setPaper('letter','landscape');
      $nombre_informe = 'cargos_sin_facturar.pdf';
      return $pdf->stream($nombre_informe);
    }

    public function rpt_cargos_sin_factura_xls(){
      $listado = DB::table('admisiones as a')
                 ->join('pacientes as p', 'a.paciente_id', 'p.id')
                 ->join('admision_cargos as ac', 'a.id', 'ac.admision_id')
                 ->join('admision_cargo_detalles as acd', 'ac.id', 'acd.admision_cargo_id')
                 ->join('users as u', 'u.id', 'acd.created_by')
                 ->groupby('a.id', 'a.admision', 'a.fecha', 'p.nombre_completo', 'acd.created_at', 'ac.precio_total', 'u.name')
                 ->select('a.id', 'a.admision', 'a.fecha', 'p.nombre_completo as paciente_nombre', 'acd.created_at as fecha_cargo', DB::raw('SUM(CASE when acd.facturar_a = "C" then acd.valor else 0 end) as cliente'), DB::raw('SUM(CASE when acd.facturar_a = "A" then acd.valor else 0 end) as aseguradora'), 'ac.precio_total', 'u.name as usuario_nombre')
                 ->where('a.empresa_id', Auth::user()->empresa_id)
                 ->whereNotExists(function ($query)
                  {
                    $query->select(DB::raw(1))
                            ->from('detalle_documentos as dd')
                            ->whereRaw('dd.admision_cargo_detalle_id = acd.id');
                  })
                 ->get();
      $titulo = 'Cargos sin factura'; //nombre de hoja
      $headings = ['Admision', 'Fecha', 'Creado por', 'Paciente', 'Fch. Cargo', 'Total Cliente', 'Total Aseguradora', 'Total Cargo'];
      $array = [];
      foreach($listado as $l)
      {
          $a['admision'] = $l->admision;
          $a['fecha'] = \Carbon\Carbon::parse($l->fecha)->format('d/m/Y');
          $a['creado por'] = $l->usuario_nombre;
          $a['paciente'] = $l->paciente_nombre;
          $a['fch cargo'] = $l->fecha_cargo;
          $a['total cliente'] = $l->cliente;
          $a['total aseguradora'] = $l->aseguradora;
          $a['precio total'] = $l->precio_total;
          $array[] = $a;
      }
      return Excel::download(new variosExport($titulo,$headings,$array), 'cargos_sin_factura.xlsx');
    }

    public function rpt_anulaciones_idx($fecha_inicial, $fecha_final){
      $hoy = Carbon::now()->format('Y-m-d');
      $del = \Carbon\Carbon::parse($fecha_inicial)->format('Y-m-d');
      $al  = \Carbon\Carbon::parse($fecha_final)->format('Y-m-d');
      $listado = DB::table('maestro_documentos as md')
                 ->join('detalle_documentos as dd', 'md.id', 'dd.maestro_documento_id')
                 ->join('users as u', 'md.anulacion_usuario_id', 'u.id')
                 ->leftjoin('admision_cargo_detalles as acd', 'dd.admision_cargo_detalle_id', 'acd.id')
                 ->leftjoin('admision_cargos as ac', 'acd.admision_cargo_id', 'ac.id')
                 ->leftjoin('admisiones as a', 'ac.admision_id', 'a.id')
                 ->where('md.empresa_id', Auth::user()->empresa_id)
                 ->whereDate('md.fecha_anulacion', '>=', $del)
                 ->whereDate('md.fecha_anulacion', '<=', $al)
                 ->select('md.fecha_anulacion', 'u.name', 'md.serie', 'md.correlativo', 'a.admision', 'md.observacion_anulacion')
                 ->get();

      return view('reportes.rpt_anulaciones_idx', compact('listado', 'hoy', 'fecha_inicial', 'fecha_final'));
    }

    public function rpt_anulaciones_pdf($fecha_inicial, $fecha_final){
      $hoy = Carbon::now()->format('Y-m-d');
      $empresa = empresa::findOrFail(Auth::user()->empresa_id);
      $del = \Carbon\Carbon::parse($fecha_inicial)->format('Y-m-d');
      $al  = \Carbon\Carbon::parse($fecha_final)->format('Y-m-d');
      $listado = DB::table('maestro_documentos as md')
                 ->join('detalle_documentos as dd', 'md.id', 'dd.maestro_documento_id')
                 ->join('users as u', 'md.anulacion_usuario_id', 'u.id')
                 ->leftjoin('admision_cargo_detalles as acd', 'dd.admision_cargo_detalle_id', 'acd.id')
                 ->leftjoin('admision_cargos as ac', 'acd.admision_cargo_id', 'ac.id')
                 ->leftjoin('admisiones as a', 'ac.admision_id', 'a.id')
                 ->where('md.empresa_id', Auth::user()->empresa_id)
                 ->whereDate('md.fecha_anulacion', '>=', $del)
                 ->whereDate('md.fecha_anulacion', '<=', $al)
                 ->select('md.fecha_anulacion', 'u.name', 'md.serie', 'md.correlativo', 'a.admision', 'md.observacion_anulacion')
                 ->get();

      ini_set('memory_limit', '-1');
      $pdf = PDF::loadView('reportes.rpt_anulaciones_pdf', compact('empresa', 'listado', 'empresa', 'fecha_inicial', 'fecha_final'));
      $pdf->setPaper('letter','portrait');
      $nombre_informe = 'anulaciones.pdf';
      return $pdf->stream($nombre_informe);
    }

    public function rpt_anulaciones_xls($fecha_inicial, $fecha_final){
      $del = \Carbon\Carbon::parse($fecha_inicial)->format('Y-m-d');
      $al  = \Carbon\Carbon::parse($fecha_final)->format('Y-m-d');
      $listado = DB::table('maestro_documentos as md')
                 ->join('detalle_documentos as dd', 'md.id', 'dd.maestro_documento_id')
                 ->join('users as u', 'md.anulacion_usuario_id', 'u.id')
                 ->leftjoin('admision_cargo_detalles as acd', 'dd.admision_cargo_detalle_id', 'acd.id')
                 ->leftjoin('admision_cargos as ac', 'acd.admision_cargo_id', 'ac.id')
                 ->leftjoin('admisiones as a', 'ac.admision_id', 'a.id')
                 ->where('md.empresa_id', Auth::user()->empresa_id)
                 ->whereDate('md.fecha_anulacion', '>=', $del)
                 ->whereDate('md.fecha_anulacion', '<=', $al)
                 ->select('md.fecha_anulacion', 'u.name', 'md.serie', 'md.correlativo', 'a.admision', 'md.observacion_anulacion')
                 ->get();
      $titulo = 'Anulaciones'; //nombre de hoja
      $headings = ['Fecha', 'Usuario', 'No. Documento', 'No. Admision', 'Razon'];
      $array = [];
      foreach($listado as $l)
      {
          $a['fecha'] = \Carbon\Carbon::parse($l->fecha_anulacion)->format('d/m/Y');
          $a['usuario'] = $l->name;
          $a['no_documento'] = $l->serie.'-'.$l->correlativo;
          $a['no_admision'] = $l->admision;
          $a['razon'] = $l->observacion_anulacion;
          $array[] = $a;
      }
      return Excel::download(new variosExport($titulo,$headings,$array), 'anulaciones.xlsx');
    }

    public function factura_pdf($id){
      //$encabezado = maestro_documento::findOrFail($id);
      $encabezado = DB::table('maestro_documentos as md')
                    ->join('pacientes as p', 'md.paciente_id', 'p.id')
                    ->join('detalle_documentos as dd', 'md.id', 'dd.maestro_documento_id')
                    ->leftjoin('admision_cargo_detalles as acd', 'dd.admision_cargo_detalle_id', 'acd.id')
                    ->leftjoin('admisiones as a', 'acd.admision_id', 'a.id')
                    ->select('md.id', 'md.fecha_emision', 'a.admision', 'md.nombre', 'md.direccion', 'md.serie', 'md.correlativo', 'md.nit', 'p.nombre_completo as paciente_nombre')
                    ->first();

      $detalle = DB::table('detalle_documentos as dd')
                 ->where('dd.maestro_documento_id', $id)
                 ->select('dd.cantidad', 'dd.precio_bruto', 'dd.descripcion')
                 ->get();
      $total_bruto       = detalle_documento::where('maestro_documento_id', $id)
                         ->select(DB::raw('SUM(IFNULL(precio_bruto,0)) as total_bruto'))
                         ->first();
      $total_descuento   = detalle_documento::where('maestro_documento_id', $id)
                         ->select(DB::raw('SUM(IFNULL(descuento,0)) as total_descuento'))
                         ->first();
      $total_recargo     = detalle_documento::where('maestro_documento_id', $id)
                         ->select(DB::raw('SUM(IFNULL(recargo,0)) as total_recargo'))
                         ->first();
      $total_neto        = detalle_documento::where('maestro_documento_id', $id)
                         ->select(DB::raw('SUM(IFNULL(precio_bruto,0)) as precio_neto'))
                         ->first();
      $letras = \NumeroALetras::convertir($total_neto->precio_neto, 'quetzales', 'centavos');
      ini_set('memory_limit', '-1');
      $pdf = PDF::loadView('reportes.rpt_factura_pdf', compact('encabezado', 'detalle', 'letras', 'total_bruto', 'total_descuento', 'total_recargo', 'total_neto'));
      $pdf->setPaper('letter','portrait');
      $nombre_informe = 'impresion_factura.pdf';
      return $pdf->stream($nombre_informe);
    }
}
