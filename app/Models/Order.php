<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model{
	protected $table = "orders";

	/**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
		'customer_name',
		'customer_email',
		'customer_mobile',
		'status'
	];
}