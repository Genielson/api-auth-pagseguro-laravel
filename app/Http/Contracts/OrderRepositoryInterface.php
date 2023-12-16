<?php

namespace App\Http\Contracts;

use Illuminate\Http\Request;

interface OrderRepositoryInterface
{

    public function getAllOrders();
    public function getOrderById(Request $request);
    public function createOrder(Request $request);
    public function deleteOrder(Request $request);

}
