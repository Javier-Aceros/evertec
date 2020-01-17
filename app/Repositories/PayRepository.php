<?php
namespace App\Repositories;

use App\Models\Configuration;

class PayRepository
{
    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Extrae el precio del producto de la base de datos
     * @return Integer
     */
    public function getProductPrice(){
        $configurations = $this->configuration->select('product_price')->first();

        return $configurations->product_price;
    }
}