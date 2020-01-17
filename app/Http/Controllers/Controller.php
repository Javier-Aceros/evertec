<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Envía la respuesta al cliente en formato json
     * @param array datos La información devuelta al cliente
     * @param integer code El código de respuesta a mostrar
     */
    protected function response($datos, $code)
    {
		return response()->json($datos, $code);
	}

    /**
     * Procesa una respuesta con errores de validación y la prepara para ser enviada
     * @param array datos Mensajes devueltos por el validador de Laravel
     * @param array datos La información devuelta al cliente
     */
    protected function validationError($datos)
    {
        return $this->response(['success' => false, 'data' => $datos], 200);
	}

    /**
     * Procesa una respuesta de error con código 409 y la prepara para ser enviada
     * @param string mensaje Mensaje de respuesta que se mostrara al cliente
     * @param array datos La información devuelta al cliente
     */
    protected function errorResponse($mensaje, $datos = null)
    {
        if(isset($datos)){
            return $this->response(
                [
                    'error' => $mensaje,
                    'data' => $datos
                ],
                409
            );
        }
        else{
            return $this->response(['error' => $mensaje], 409);
        }
    }

    /**
     * Procesa una respuesta exitosa y la prepara para ser enviada
     * @param string mensaje Mensaje de respuesta que se mostrara al cliente
     * @param array datos La información devuelta al cliente
     */
    protected function successResponse($mensaje = null, $datos = null)
    {
        return $this->response(['success' => true, 'mensaje' => $mensaje, 'data' => $datos], 200);
    }
}