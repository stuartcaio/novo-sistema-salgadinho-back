<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Http\Controllers\Traits\DatabaseFunctions;

class PermissionController extends Controller
{
    use DatabaseFunctions;

    public function __construct(){
        $this->__dbConstruct("permissions");
    }

    public function index(){
        try{
            return Permission::getPermissions();
        } catch (Exception $e){
            return $e;
        }
    }

    public function store(Request $request){
        try{
            $permissionExists = $this->FindByInsertedOneProperty("name", $request->name);

            if(!$permissionExists){
                $this->save(["name" => $request->name, "description" => $request->description, "resource" => $request->resource, "action" => $request->action]);

                return response()->json(["message" => "Permissão cadastrada com sucesso!"]);
            }

            return response()->json(["message" => "Já existe uma permissão com este nome."]);
        } catch(Exception $e){
            return $e;
        }
    }

    public function update(Request $request, int $id){
        try{
            $permissionWithInsertedID = $this->FindByInsertedOneProperty("id", $id);

            if($permissionWithInsertedID){
                $this->updateByID(["name" => $request->name, "description" => $request->description, "resource" => $request->resource, "action" => $request->action], $id);

                return response()->json(["message" => "Permissão atualizada com sucesso!"]);
            }

            return response()->json(["message" => "Não existe uma permissão com este ID."]);
        } catch(Exception $e){
            return $e;
        }
    }

    public function destroy(int $id){
        try{
            $permissionWithInsertedID = $this->FindByInsertedOneProperty("id", $id);

            if($permissionWithInsertedID){
                $this->delete($id);

                return response()->json(["message" => "Permissão deletada com sucesso!"]);
            }

            return response()->json(["message" => "Não existe uma permissão com este ID."]);
        } catch(Exception $e){
            return $e;
        }
    }

    public function show(int $id){
        try{
            $permissionWithInsertedID = $this->FindByInsertedOneProperty("id", $id);

            if($permissionWithInsertedID){
                return $permissionWithInsertedID;
            }

            return response()->json(["message" => "Não existe uma permissão com este ID."]);
        } catch(Exception $e){
            return $e;
        }
    }
}
