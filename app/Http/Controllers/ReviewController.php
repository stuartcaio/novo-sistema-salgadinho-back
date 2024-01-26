<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Http\Controllers\Traits\DatabaseFunctions;
use DB;

class ReviewController extends Controller
{
    use DatabaseFunctions;

    public function __construct(){
        $this->__dbConstruct("reviews");
    }

    public function index(){
        return Review::getReviews();
    }

    public function store(Request $request){
        try{
            $review = new Review;

            $this->save(["reviewValue" => $request->reviewValue, "description" => $request->description, "employee_id" => $request->employee_id, "company_id" => $request->company_id]);
            
            return response()->json(["message" => "Review cadastrada com sucesso!"]);
        } catch (Exception $e){
            return $e;
        }
    }

    public function update(Request $request, int $id){
        try{
            $reviewExists = $this->FindByInsertedOneProperty("id", $id);

            if(!$reviewExists){
                $this->updateByID(["reviewValue" => $request->reviewValue, "description" => $request->description], $id);

                return response()->json(["message" => "Review atualizada com sucesso!"]);
            }

            return response()->json(["message" => "Não existe uma review com este ID."]);
        } catch(Exception $e){
            return $e;
        }
    }

    public function destroy(int $id){
        $reviewWithInsertedIdExists = $this->FindByInsertedOneProperty("id", $id);

        if($reviewWithInsertedIdExists){
            $this->delete($id);

            return response()->json(["message" => "Review deletada com sucesso!"]);
        }

        return response()->json(["message" => "Não existe uma review com este ID."]);
    }

    public function show(int $id){
        $reviewWithInsertedIdExists = $this->FindByInsertedOneProperty("id", $id);

        if($reviewWithInsertedIdExists){
            return $reviewWithInsertedIdExists;
        }

        return response()->json(["message" => "Não existe uma review com este ID."]);
    }
}
