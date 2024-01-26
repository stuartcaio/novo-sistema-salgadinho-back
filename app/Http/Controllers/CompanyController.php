<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Http\Controllers\Traits\DatabaseFunctions;

class CompanyController extends Controller
{
    use DatabaseFunctions;

    public function __construct(){
        $this->__dbConstruct("companies");
    }

    public function index(){
        return Company::getCompanies();
    }

    public function store(Request $request){
        $companyExists = $this->FindByInsertedOneProperty("name", $request->name);

        if(!$companyExists){
            try{
                $company = new company;

                $this->save(["name" => $request->name, "address" => $request->address]);

                return response()->json(["message" => "Empresa cadastrado com sucesso!"]);
            } catch(Exception $e){

            }
        }

        return response()->json(["message" => "Este empresa já foi cadastrado."]);
    }

    public function update(Request $request, $id){
        $companyWithInsertedIDExists = $this->FindByInsertedOneProperty("id", $id);

        if($companyWithInsertedIDExists){
            $this->updateByID(["name" => $request->name, "address" => $request->address], $id);

            return response()->json(["message" => "Empresa atualizado com sucesso!"]);
        }

        return response()->json(["message" => "Não existe um empresa com este ID."]);
    }

    public function destroy($id){
        $companyWithInsertedIDExists = $this->FindByInsertedOneProperty("id", $id);

        if($companyWithInsertedIDExists){
            $this->delete($id);

            return response()->json(["message" => "Empresa deletado com sucesso!"]);
        }

        return response()->json(["message" => "Não existe um empresa com este ID."]);
    }

    public function show($id){
        $company = $this->FindByInsertedOneProperty("id", $id);

        return $company;
    }
}
