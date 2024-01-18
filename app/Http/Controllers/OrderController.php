<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Controllers\Traits\DatabaseFunctions;
use DB;

class OrderController extends Controller
{
    use DatabaseFunctions;

    public function __construct(){
        $this->__dbConstruct("orders");
    }

    public function index(){
        return Order::getOrders();
    }

    public function store(Request $request){
        try{
            $order = new Order;

            $this->save(["employee_id" => $request->employee_id, "company_id" => $request->company_id]);

            return response()->json(["message" => "Pedido realizado com sucesso!"]);
        } catch (Exception $e){
            return $e;
        }
    }

    public function destroy($id){
        $orderWithInsertedId = $this->FindByInsertedOneProperty("id", $id);

        try{
            $this->delete($orderWithInsertedId);
        } catch (Exception $e){
            return $e;
        }
    }

    public function show($id){
        $orderWithInsertedId = $this->FindByInsertedOneProperty("id", $id);

        try {
            return $orderWithInsertedId;
        } catch (Exception $e){
            return $e;
        }
    }
}
