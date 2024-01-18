<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Http\Controllers\Traits\DatabaseFunctions;
use App\Http\Controllers\Traits\Permission;

class RoleController extends Controller
{
    use DatabaseFunctions;
    use Permission;

    public function __construct(){
        $this->__dbConstruct("roles");
    }

    public function index(){
        try{
            return Role::getRolesWithPermissions();
        } catch(Exception $e){
            return $e;
        }
    }

    public function store(Request $request){
        try{
            $roleExists = $this->FindByInsertedOneProperty("name", $request->name);

            if(!$roleExists){
                $role = new Role;

                $this->save(["name" => $request->name, "description" => $request->description]);

                return response()->json(["message" => "Cargo cadastrado com sucesso!"]);
            }

            return response()->json(["message" => "Já existe um cargo com este ID."]);
        } catch(Exception $e){
            return $e;
        }
    }

    public function update(Request $request, int $id){
        try{
            $roleWithInsertedIDExists = $this->FindByInsertedOneProperty("id", $id);

            if($roleWithInsertedIDExists){
                $this->updateByID(["name" => $request->name, "description" => $request->description], $id);

                if(count($request->permissions) >= 1){
                    $this->__permissionConstruct("roles_permissions");
                    $this->setPermissions($request->permissions, $roleWithInsertedIDExists);
                }

                return response()->json(["message" => "Cargo atualizado com sucesso!"]);
            }

            return response()->json(["message" => "Não existe um cargo com este ID."]);
        } catch(Exception $e){
            return $e;
        }
    }

    public function destroy(int $id){
        try{
            $roleWithInsertedIDExists = $this->FindByInsertedOneProperty("id", $id);

            if($roleWithInsertedIDExists){
                $this->delete($id);
            }

            return response()->json(["message" => "Não existe um cargo com este ID."]);
        } catch(Exception $e){
            return $e;
        }
    }

    public function show(int $id){
        try{
            $roleWithInsertedIDExists = $this->FindByInsertedOneProperty("id", $id);

            if($roleWithInsertedIDExists){
                return $roleWithInsertedIDExists;
            }

            return response()->json(["message" => "Nãõ existe um cargo com este ID."]);
        } catch(Exception $e){
            return $e;
        }
    }
}
