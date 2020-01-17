<?php
namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Services\PayService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class OrderController extends Controller
{
	public function __construct(OrderService $orderService, PayService $payService)
	{
		$this->orderService = $orderService;
        $this->payService = $payService;
	}

    /**
     * Carga la vista con el listado de todas las órdenes en el sistema
     */
    public function index()
    {
        Carbon::setLocale('es');

        $orders = $this->orderService->all();
        foreach ($orders as $order) {
            // Agrega información de hace cuanto se creó la orden
            $diff = new Carbon($order->created_at);
            $order->diff = $diff->diffForHumans();

            // Traduce el estado en que se encuentra la orden
            switch ($order->status) {
                case 'CREATED':
                    $order->status_es = 'Pendiente';
                    break;
                
                case 'PAYED':
                    $order->status_es = 'Pagada';
                    break;
                
                case 'REJECTED':
                    $order->status_es = 'Rechazado';
                    break;
                
                default:
                    $order->status_es = 'Pendiente';
                    break;
            }
        }

        return view('order.index', ['orders' => $orders]);
    }

	/**
	 * Carga el formulario donde se ingresan los datos de la compra
     */
    public function create()
    {
    	return view('order.create');
    }

    /**
     * Valida la información recibida de la compra y almacena la información
     * @param string nombre Nombre del cliente
     * @param string email Email del cliente
     * @param integer celular Celular del cliente
     */
    public function store(Request $request)
    {
    	$rules = [
    		'nombre' => 'required|string|max:80',
    		'email' => 'required|email|max:120',
    		'celular' => 'required|digits:10|regex:/^[0-9]{10}$/'
    	];

    	$mensajes = [
    		'celular.regex' => 'El celular debe ser un número entero de diez dígitos sin puntos, comas, ni espacios.'
    	];

        // Valida la información recibida
    	$validator = Validator::make($request->all(), $rules, $mensajes);
        if($validator->fails()){
            return $this->validationError($validator->messages());
        }

        try {
            // Almacena la información de la orden
            $order = $this->orderService->store($request->all());

            return $this->successResponse('Información guardada', $order);
        }
        catch (Exception $e) {
            return $this->errorResponse('Se presentó un error en su solicitud, inténtelo nuevamente');
        }
    }

    /**
     * Carga la vista con los detalles de la compra que permite realizar el pago
     * @param integer order_id Id de la orden de compra
     */
    public function show($order_id)
    {
        $rules = [
            'order_id' => 'required|exists:orders,id'
        ];

        // Valida la información recibida
        $validator = Validator::make(['order_id' => $order_id], $rules);
        if($validator->fails()){
            return $this->validationError($validator->messages());
        }

        $order = $this->orderService->show($order_id);

        return view('order.show', ['order' => $order]);
    }

    /**
     * Carga una vista con detalles de la orden y el estado en que se encuentra
     * @param integer order_id Id de la orden de compra
     */
    public function status($order_id)
    {
        $rules = [
            'order_id' => 'required|exists:orders,id'
        ];

        // Valida la información recibida
        $validator = Validator::make(['order_id' => $order_id], $rules);
        if($validator->fails()){
            return $this->validationError($validator->messages());
        }

        try {
            $order = $this->orderService->show($order_id);

            // Consulta externamente el estado de la transacción
            $requestInformation = $this->payService->getRequestInformation($order->id, $order->request_id);
        }
        catch (Exception $e) {
            return $this->errorResponse('Se presentó un error en su solicitud, inténtelo nuevamente');
        }

        // Agrega la información obtenida
        $order->status = $requestInformation['status'];
        $order->request_status = $requestInformation['request_status'];
        $order->message = $requestInformation['message'];

        return view('order.status', ['order' => $order]);
    }
}