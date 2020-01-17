<?php
namespace Tests\Unit;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Faker\Factory;
use Tests\TestCase;

class OrderTest extends TestCase
{
	use DatabaseTransactions;

    /**
     * A basic test example.
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
        $this->get('/');
    }

    public function testGetAllOrdersView()
    {
        $response = $this->getJson('/order/index');
        $response
        	->assertStatus(200)
        	->assertViewIs('order.index')
        	->assertViewHas('orders');
    }

    public function testGetCreateOrdersView()
    {
        $response = $this->getJson('/');
        $response
        	->assertStatus(200)
        	->assertViewIs('order.create');
    }

    public function testAddOrder()
    {
    	$faker = Factory::create();
    	$data = [
            'nombre' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'celular' => abs($faker->numberBetween(3000000000, 3199999999)),
        ];

        $response = $this->postJson('/order/store', $data);
        $response
            ->assertStatus(200)
            ->assertJsonStructure(
            	[
                	'success',
					'mensaje',
					'data' => [
						'id',
						'customer_mobile',
						'customer_name',
						'customer_email',
						'created_at'
					]
	            ]
	        );
    }

    public function testErrorAddingOrder()
    {
    	$faker = Factory::create();
    	$data = [
            'nombre' => null,
            'email' => null,
            'celular' => null,
        ];

        $response = $this->postJson('/order/store', $data);
        $response
            ->assertStatus(200)
            ->assertJsonStructure(
            	[
                	'success',
					'data' => [
						'nombre',
						'email',
						'celular'
					]
	            ]
	        );
    }

    public function testGetShowOrderView()
    {
    	$order = \App\Models\Order::first();

    	if(isset($order)){
	    	$response = $this->getJson('/order/show/'.$order->id);
	        $response
	        	->assertStatus(200)
	        	->assertViewIs('order.show')
	        	->assertViewHas('order');
    	}
    	else{
    		$response = $this->getJson('/order/show/1');
    		$response
	        	->assertStatus(200)
	        	->assertJsonStructure(
	            	[
	                	'success',
						'data' => [
							'order_id'
						]
		            ]
		        );
    	}
    }

    public function testGetStatusOrderView()
    {
    	$order = \App\Models\Order::first();

    	if(isset($order)){
	    	$response = $this->getJson('/order/status/'.$order->id);
	        $response
	        	->assertStatus(200)
	        	->assertViewIs('order.status')
	        	->assertViewHas('order');
        }
        else{
        	$response = $this->getJson('/order/status/1');
	        $response
	        	->assertStatus(200)
	        	->assertJsonStructure(
	            	[
	                	'success',
						'data' => [
							'order_id'
						]
		            ]
		        );
        }
    }
}