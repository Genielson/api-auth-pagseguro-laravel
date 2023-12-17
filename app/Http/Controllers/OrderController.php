<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

/** @package App\Http\Controllers */
class OrderController extends Controller
{
    private $repository;
    use ApiResponser;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(OrderRepository $orderRepository){
        $this->repository = $orderRepository;
    }
    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function isRegisterValid(Request $request)
    {

        return  $this->validate(
            $request,
            [
                'name' => 'required',
                'description' => 'required',
                'amount_hour' => 'required',
                'amount_module' => 'required',
                'user_id' => 'required'
            ]
        );
    }

    public function index(){

        try{
            return $this->repository->getAllOrders();
        }catch (Exception $e){
            return response()->json(['mensagem' => 'Houve um erro'],500);
        }

    }


      /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
       * @throws ValidationException
     */

    public function show(Request $request){

        try{
            return $this->repository->getOrderById($request);
        }catch (\Exception $e){
            return response()->json(['mensagem'=>"Houve um erro"], 500);
        }

    }

    public function listUserOrder(){
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->get();
        if(count($orders) > 0){
            return response()->json([$orders,200]);
        }else{
            return response()->json(['mensagem'=>"Não encontramos nenhum pedido"], 404);
        }
    }

    /**
     * @param Request $request
     * @return App\Traits\Iluminate\Http\Response|void
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        if ($this->isRegisterValid($request)) {

            if(Order::create($request->all())){
                return response()->json(['mensagem'=>' Pedido criado com sucesso '], 201);
            }else{
                return response()->json(['mensagem' => 'Erro ao criar o pedido'], 500);
            }

        }else{
            return response()->json(['mensagem'=>'Algum parametro não foi enviado corretamente'],404);
        }
    }

     /**
     * @param Request $request
     * @return App\Traits\Iluminate\Http\Response|void
     * @throws ValidationException
     */
    public function destroy(Request $request){
        $user = Auth::user();
        if(isset($request->id)){
            if($user->id == $request->user_id){
                $order = Order::findOrFail($request->id);
                $order->delete();
                return response()->json(["mensagem" => "Pedido deletado com sucesso"], 200);
            }
        }else{
            return response()->json(['mensagem'=>'Algum parametro não foi enviado
            corretamente'],404);
        }
    }

}
