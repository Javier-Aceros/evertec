<?php
namespace App\Services;

use App\Repositories\OrderRepository;
use App\Repositories\PayRepository;
use DB;
use Carbon\Carbon;

class PayService
{
    public function __construct(OrderRepository $orderRepository, PayRepository $payRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->payRepository = $payRepository;
    }

    /**
     * Establece la conexión con el método session de placetopay y envía la información de la orden de compra
     * @param string customer_name
     * @param string customer_email
     * @param string customer_mobile
     * @param integer id Id de la orden de compra
     * @param string ip Ip del cliente
     * @param string browser Explorador que usa el cliente
     */
    public function session(array $request)
    {
        $product_price = $this->payRepository->getProductPrice();

        // Crea array con la información solicitada para la conexión
        $info = [
            'locale' => 'es_CO',
            'buyer' => [
                'name' => $request['customer_name'],
                'email' => $request['customer_email'],
                'mobile' => $request['customer_mobile']
            ],
            'payment' => [
                'reference' => $request['id'],
                'amount' => [
                    'currency' => 'COP',
                    'total' => $product_price
                ],
                'allowPartial' => false
            ],
            'expiration' => Carbon::now()->addDay()->toIso8601String(),
            'ipAddress' => $request['ip'],
            'userAgent' => $request['browser'],
            'returnUrl' => env('PLACETOPAY_RETURNURL').$request['id'],
            'cancelUrl' => env('PLACETOPAY_CANCELURL').$request['id'],
            'skipResult' => false
        ];

        // Realiza la conexión con placetopay y la retorna en forma de objeto
        $response = json_decode($this->conexion('', $info));

        // Valida la respuesta obtenida
        if(isset($response->status->status) && $response->status->status === 'OK'){
            // Almacena el request_id obtenido de placetopay
            $saveRequestId = $this->orderRepository->setRequestId($request['id'], $response->requestId);
            if(!$saveRequestId){
                return false;
            }

            return ['processUrl' => $response->processUrl];
        }
        else{
            return false;
        }
    }

    /**
     * Establece la conexión con el metodo getRequestInformation de placetopay
     * @param integer order_id Id de la orden de compra
     * @param integer requestId Id de la request a consultar
     */
    public function getRequestInformation($order_id, $requestId)
    {
        // Crea array con la información solicitada para la conexión
        $info = [
            'requestId' => $requestId,
        ];

        // Realiza la conexión con placetopay y la retorna en forma de objeto
        $response = json_decode($this->conexion($requestId, $info));

        // Valida la respuesta obtenida de placetopay
        if(isset($response->status->status)){
            switch ($response->status->status) {
                case 'OK':
                    $status = 'CREATED';
                    break;
                
                case 'FAILED':
                    $status = 'CREATED';
                    break;
                
                case 'APPROVED':
                    $status = 'PAYED';
                    break;
                
                case 'APPROVED_PARTIAL':
                    $status = 'CREATED';
                    break;
                
                case 'PARTIAL_EXPIRED':
                    $status = 'CREATED';
                    break;
                
                case 'REJECTED':
                    $status = 'REJECTED';
                    break;
                
                case 'PENDING':
                    $status = 'CREATED';
                    break;
                
                case 'PENDING_VALIDATION':
                    $status = 'CREATED';
                    break;
                
                case 'REFUNDED':
                    $status = 'REJECTED';
                    break;
            }

            // Almacena el estado de la orden y de la solicitud 
            $saveStatus = $this->orderRepository->setStatus($order_id, $status);
            $saveRequestStatus = $this->orderRepository->setRequestStatus($order_id, $response->status->status);
            if(!$saveStatus || !$saveRequestStatus){
                return false;
            }

            return [
                'status' => $status,
                'request_status' => $response->status->status,
                'message' => $response->status->message
            ];
        }
        else{
            return false;
        }
    }

    /**
     * Crea las conexiones a placetopay
     * @param string metodo Nombre del directorio con el que se va a establecer la conexión
     * @param array info Campos que van a ser enviados en la petición
     */
    private function conexion($metodo, array $info){
        // Obtiene la información de autenticación
        $authData = $this->authData();

        // Convierte la información al formato adecuado
        $dataJson = json_encode( array_merge($info, $authData) );

        $url = env('PLACETOPAY_URL').$metodo;

        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $dataJson);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));

        return curl_exec($curl_handle);
    }

    /**
     * Prepara la información de autenticación con placetopay
     * @return array Información de autenticación
     */
    private function authData(){
        $seed = Carbon::now()->toIso8601String();
        $nonce = random_int(0, 9999999);
        $tranKey = base64_encode(sha1($nonce.$seed.env('PLACETOPAY_TRANKEY'), true));
        $nonce = base64_encode($nonce);

        return $data = array(
            'auth' => [
                'login' => env('PLACETOPAY_LOGIN'),
                'tranKey' => $tranKey,
                'nonce' => $nonce,
                'seed' => $seed
            ]
        );
    }
}