<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehiculo;
use App\Models\Parqueo;
use DB;

class ParqueoController extends Controller
{
	/**
     * Se comprueba si existe el vehiculo mediante la placa.
     * Si no existe, se crea el vehiculo con sus datos y se registra la entrada.
     * Si existe, se comprueba que no exista registro de entrada sin salida.
     * Si existe entrada sin salida, retorna mensaje de error que existe un vehiculo con esa placa sin salida
     * Si no existe entrada sin salida, se registra una nueva entrada al estacionamiento  
     */
    public function registrarEntrada(Request $request) {
    	$vehiculo = Vehiculo::where(['chapa' => $request->get('placa')])->first();
    	if($vehiculo == null) {
    		$vehiculo = new Vehiculo();
    		$vehiculo->chapa = $request->get('placa');
    		$vehiculo->save();
    		$parqueo = new Parqueo();
    		$parqueo->entrada = time();
    		$parqueo->vehiculo_id = $vehiculo->id;
    		$parqueo->save();
    		return response()->json([
    			"entrada" => [
    				"vehiculo" => $vehiculo->chapa,
    				"dia" => date("m/d/Y", $parqueo->entrada),
    				"hora" => date("H:i", $parqueo->entrada)
    			]
    		], 200);
    	}
    	else {
    		$parqueo = Parqueo::with(['vehiculos'])->where(['vehiculo_id' => $vehiculo->id])->whereNull('salida')->first();
    		if($parqueo != null)
    			return response()->json([
	    			"error" => [
	    				"vehiculo" => $request->get('placa'),
	    				"dia" => date("m/d/Y", $parqueo->vehiculos->entrada),
	    				"hora" => date("H:i", $parqueo->vehiculos->entrada),
	    				"mensaje" => "Existe un vehiculo sin salida registrada en el estacionamiento con esta placa"
	    			]
	    		], 500);
    		else {
    			$parqueo = new Parqueo();
	    		$parqueo->entrada = time();
	    		$parqueo->vehiculo_id = $vehiculo->id;
	    		$parqueo->save();
	    		return response()->json([
	    			"entrada" => [
	    				"vehiculo" => $vehiculo->chapa,
	    				"dia" => date("m/d/Y", $parqueo->entrada),
	    				"hora" => date("H:i", $parqueo->entrada)
	    			]
	    		], 200);
    		}
    	}
    }

    /**
     * Se comprueba si existe el vehiculo estacionado mediante la placa.
     * Si no existe, retorna mensaje de error que no existe un vehiculo estacionado con esa placa.
     * Si existe, se registra la salida y se calcula el importe a pagar por el cliente.
     * Retorna el Importe a pagar 
     */
    public function registrarSalida(Request $request) {
    	$parqueo = Parqueo::whereHas('vehiculos', function($q) use($request) {
    		$q->where(['chapa' => $request->get('placa')]);
    	})->whereNull('salida')->first();
    	if($parqueo == null)
    		return response()->json([
	    		"error" => [
	    			"vehiculo" => $request->get('placa'),
	    			"mensaje" => "No existe un vehiculo estacionado con esta placa"
	    		]
	    	], 500);
    	else {
    		$salida = time();
			$intervalo = abs($parqueo->entrada - $salida);
			$minutos = round($intervalo / 60);
			$importe = round($minutos * 0.5, 2);
			$parqueo->salida = $salida;
			$parqueo->minutos = $minutos;
			$parqueo->importe = $importe;
    		$parqueo->save();
    		return response()->json([
	    		"salida" => [
	    			"vehiculo" => $request->get('placa'),
	    			"dia_entrada" => date("m/d/Y", $parqueo->entrada),
	    			"hora_entrada" => date("H:i", $parqueo->entrada),
	    			"dia_salida" => date("m/d/Y", $parqueo->salida),
	    			"hora_salida" => date("H:i", $parqueo->salida),
	    			"minutos_estacionado" => $minutos,
	    			"importe" => $importe
	    		]
	    	], 200);
    	}
    }

    /**
     * Resumen por cliente del tiempo estacionado y el importe pagado 
     */
    public function informeImporte() {
    	$data = Parqueo::with(['vehiculos'])->select(
                "vehiculo_id",
                DB::raw("SUM(minutos) as tiempo"),
                DB::raw("SUM(importe) as pagado")
            )
    		->whereNotNull('salida')
    		->groupBy('vehiculo_id')
    		->get();
        $resultado = [];
        for($i = 0; $i < count($data); $i++) {
            array_push($resultado, ["placa" => $data[$i]['vehiculos']['chapa'], "tiempo" => $data[$i]['tiempo'], "pagado" => $data[$i]['pagado']]);
        }
    	return response()->json($resultado);
    }

    /**
     * Listado de los 3 vehiculos que más usan el estacionamiento comparado con el tiempo de estancia total
     */
    public function listadoTiempo() {
        $data = Parqueo::with(['vehiculos'])->select(
                "vehiculo_id",
                DB::raw("SUM(minutos) as tiempo")
            )
            ->whereNotNull('salida')
            ->groupBy('vehiculo_id')
            ->orderBy('tiempo', 'desc')
            ->limit(3)
            ->get();
        $resultado = [];
        for($i = 0; $i < count($data); $i++) {
            array_push($resultado, ["placa" => $data[$i]['vehiculos']['chapa'], "tiempo" => $data[$i]['tiempo']]);
        }
        return response()->json($resultado);
    }

    /**
     * Listado de los 3 vehiculos que más usan el estacionamiento comparado con la cantidad de veces que han estacionado
     */
    public function listadoUso() {
        $data = Parqueo::with(['vehiculos'])->select(
                "vehiculo_id",
                DB::raw("COUNT(vehiculo_id) as cantidad")
            )
            ->whereNotNull('salida')
            ->groupBy('vehiculo_id')
            ->orderBy('cantidad', 'desc')
            ->limit(3)
            ->get();
        $resultado = [];
        for($i = 0; $i < count($data); $i++) {
            array_push($resultado, ["placa" => $data[$i]['vehiculos']['chapa'], "cantidad" => $data[$i]['cantidad']]);
        }
        return response()->json($resultado);
    }
}
