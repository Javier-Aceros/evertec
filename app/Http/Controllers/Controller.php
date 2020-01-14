<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function response($datos, $code){
		return response()->json($datos, $code);
	}

    /**
     * @param array datos Mensajes devueltos por el validador de Laravel
     */
    protected function validationError($datos){
        return $this->response(['success' => false, 'data' => $datos], 200);
	}

    /**
     * @param string mensaje Mensaje de respuesta que se mostrara al cliente
     * @param array datos La información devuelta al cliente
     */
    protected function successResponse($mensaje = null, $datos = null){
        return $this->response(['success' => true, 'mensaje' => $mensaje, 'data' => $datos], 200);
    }
}