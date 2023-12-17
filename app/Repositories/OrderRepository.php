<?php

namespace App\Repositories;

use App\Http\Contracts\OrderRepositoryInterface;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderRepository implements OrderRepositoryInterface
{

    public function getAllOrders()
    {
        try{
            $orders = Order::all();
            if(count($orders) > 0){
                return response()->json([$orders,200]);
            }else{
                return response()->json(['mensagem'=>"Não encontramos nenhum pedido"], 404);
            }
        }catch(\Exception $e){
            return response()->json(['mensagem'=>"Houve um erro"], 500);
        }
    }

    public function getOrderById(Request $request)
    {
        try{

            if(isset($request->id) && $request->id != NULL){
                $orders = Order::findOrFail($request->id);
                if(count($orders) > 0){
                    return response()->json([$orders,200]);
                }else{
                    return response()->json(['mensagem'=>"Não encontramos nenhum pedido"], 404);
                }
            }else{
                return response()->json(['mensagem'=>"Por favor, envie o parametro para busca"],
                    404);
            }

        }catch (\Exception $e){
            return response()->json(['mensagem'=>"Houve um erro"], 500);
        }
    }

    public function createOrder(Request $request)
    {
       try{
           if(Order::create($request->all())){
               return response()->json(['mensagem'=>' Pedido criado com sucesso '], 201);
           }else{
               return response()->json(['mensagem' => 'Erro ao criar o pedido'], 500);
           }
       }catch(\Exception $e){
           return response()->json(['mensagem'=>"Houve um erro"], 500);
       }
    }

    public function deleteOrder(Request $request)
    {
        // TODO: Implement deleteOrder() method.
    }
}
