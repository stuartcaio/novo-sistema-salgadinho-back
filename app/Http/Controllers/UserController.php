<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Traits\DatabaseFunctions;
use DB;

class UserController extends Controller
{
    use DatabaseFunctions;

    public function __construct(){
        $this->__dbConstruct("users");
    }

    public function index(){
        return User::getUsers();
    }

    public function store(Request $request){
        $userExists = $this->FindByInsertedOneProperty("email", $request->email);

        if(!$userExists){
            try{
                $user = new User;

                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = $request->password;

                $user->save();

                return response()->json(["message" => "Usuário adicionado com sucesso!"]);
            } catch(Exception $e){
                return $e->getMessage();
            }
        }

        return response()->json(["message" => "Já existe um usuário com este e-mail."]);
    }

    public function update(Request $request, int $id){
        $userWithInsertedIDExists = $this->FindByInsertedOneProperty("id", $id);
        $userWithInsertedEmailOrNameExists = $this->FindByInsertedProperties(["name", "email"], [$request->name, $request->email]);

        if($userWithInsertedEmailOrNameExists){
            return response()->json(["message" => "Já existe um usuário com este nome ou e-mail."]);
        }


        DB::table("users as u")
          ->where("u.id", $id)
          ->update([
                "u.name" => $request->name,
                "u.email" => $request->email,
                "u.password" => $request->password
            ]);
    
        return ["message" => "Usuário atualizado com sucesso!"];
    }

    public function destroy($id){
        $userWithInsertedIDExists = $this->FindByInsertedOneProperty("id", $id);

        if($userWithInsertedIDExists){
            $this->delete();

            return ["message" => "Usuário deletado com sucesso!"];
        }

        return ["message" => "Não existe um usuário com este ID"];
    }

    public function show(int $id){
        $user = $this->FindByInsertedOneProperty("id", $id);

        return $user;
    }
}
