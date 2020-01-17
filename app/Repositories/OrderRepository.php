<?php
namespace App\Repositories;

use App\Models\Order;

class OrderRepository
{
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Crea una nueva orden de pedido
     * @return Model Order
     */
    public function store(array $data)
    {
        return $this->order->create($data);
    }

    /**
     * Retorna toda la información de todas las órdenes de compra en la base de datos
     */
    public function all()
    {
        return $this->order->paginate(3);
    }

    /**
     * Retorna la orden consultada
     * @return Model Order
     */
    public function show($order_id)
    {
        return $this->order->find($order_id);
    }

    /**
     * Agrega el id de solicitud devuelto por placetopay
     * @param integer order_id Id de la orden de compra
     * @param integer request_id Id de solicitud devuelto por placetopay
     * @return boolean
     */
    public function setRequestId($order_id, $request_id)
    {
        $order = $this->order->find($order_id);
        $order->request_id = $request_id;

        return $order->save();
    }

    /**
     * Modifica el estado de la orden de compra
     * @param integer order_id Id de la orden de compra
     * @param string status Estado de la orden de compra
     * @return boolean
     */
    public function setStatus($order_id, $status)
    {
        $order = $this->order->find($order_id);
        $order->status = $status;

        return $order->save();
    }

    /**
     * Agrega el estado de solicitud devuelto por placetopay
     * @param integer order_id Id de la orden de compra
     * @param string status Estado de solicitud devuelto por placetopay
     * @return boolean
     */
    public function setRequestStatus($order_id, $status)
    {
        $order = $this->order->find($order_id);
        $order->request_status = $status;

        return $order->save();
    }
}