<?php
namespace App\Http\Controllers;

use App\Services\PayService;
use Illuminate\Http\Request;

class PayController extends Controller
{
	public function __construct(PayService $payService)
	{
		$this->payService = $payService;
	}

	/**
	 * Carga el formulario externo donde se ingresan los datos de la compra
     */
    public function create(Request $request)
    {
        $response = $this->payService->session($request->all());
        if(!$response){
            $this->errorResponse('Se presentó un error y no se pudo completar la solicitud, intente nuevamente');
        }

        return $this->successResponse("Será redirigido a la pasarela de pago", $response);
    }
}