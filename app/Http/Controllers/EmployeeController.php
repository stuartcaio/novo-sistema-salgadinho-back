<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Http\Controllers\Traits\DatabaseFunctions;

class EmployeeController extends Controller
{
    use DatabaseFunctions;

    public function __construct(){
        $this->__dbConstruct("employees");
    }

    public function index(){
        return Employee::getEmployees();
    }

    public function store(Request $request){
        $employeeExists = $this->FindByInsertedOneProperty("name", $request->name);

        if(!$employeeExists){
            try{
                $employee = new Employee;

                $this->save(["name" => $request->name, "dateOfBirth" => $request->dateOfBirth, "dateOfSigning" => $request->dateOfSigning]);

                return response()->json(["message" => "Funcionário cadastrado com sucesso!"]);
            } catch(Exception $e){
                return $e;
            }
        }

        return response()->json(["message" => "Este funcionário já foi cadastrado."]);
    }

    public function update(Request $request, $id){
        $employeeWithInsertedIDExists = $this->FindByInsertedOneProperty("id", $id);

        if($employeeWithInsertedIDExists){
            $this->updateByID(["name" => $request->name, "dateOfBirth" => $request->dateOfBirth, "dateOfSigning" => $request->dateOfSigning], $id);

            return response()->json(["message" => "Funcionário atualizado com sucesso!"]);
        }

        return response()->json(["message" => "Não existe um funcionário com este ID."]);
    }

    public function destroy($id){
        $employeeWithInsertedIDExists = $this->FindByInsertedOneProperty("id", $id);

        if($employeeWithInsertedIDExists){
            $this->delete($id);

            return response()->json(["message" => "Funcionário deletado com sucesso!"]);
        }

        return response()->json(["message" => "Não existe um funcionário com este ID."]);
    }

    public function show($id){
        $employee = $this->FindByInsertedOneProperty("id", $id);

        return $employee;
    }
}
