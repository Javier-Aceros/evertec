<?php
namespace App\Services;

use App\Repositories\OrderRepository;
use App\Repositories\PayRepository;
use Carbon\Carbon;

class OrderService
{
    public function __construct(OrderRepository $orderRepository, PayRepository $payRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->payRepository = $payRepository;
    }

    /**
     * Prepara la información recibida de la compra para ser almacenada en la base de datos
     * @param string nombre Nombre del cliente
     * @param string email Email del cliente
     * @param integer celular Celular del cliente
     */
    public function store(array $data)
    {
        // Cambia los nombres de las llaves recibidas por el almacenado en la base de datos
        $data = $this->changekeyname($data, 'celular', 'customer_mobile');
        $data = $this->changekeyname($data, 'nombre', 'customer_name');
        $data = $this->changekeyname($data, 'email', 'customer_email');

        return $this->orderRepository->store($data);
    }

    /**
     * Devuelve todas las órdenes en el sistema
     */
    public function all()
    {
        return $this->orderRepository->all();
    }

    /**
     * Trae toda la información de la orden $order_id
     * @param integer order_id Id de la orden de compra
     */
    public function show($order_id){
        $order = $this->orderRepository->show($order_id);

        // Traduce la hora de creación
        Carbon::setLocale('es');
        $diff = new Carbon($order->created_at);
        $order->diff = $diff->diffForHumans();

        // Obtiene el precio del producto
        $order->product_price = $this->payRepository->getProductPrice();

        return $order;
    }

    /**
     * Cambia el nombre $oldkey por $newkey en el $array dado
     * @param array array Array a procesar
     * @param string oldkey Nombre de llave a reemplazar
     * @param string newkey Nuevo nombre de llave
     */
    private function changekeyname(array $array, $oldkey, $newkey)
    {
        $array[$newkey] = $array[$oldkey];
        unset($array[$oldkey]);

        return $array;
    }
}