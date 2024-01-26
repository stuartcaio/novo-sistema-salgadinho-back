<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Traits\DatabaseFunctions;   
use App\Http\Controllers\Traits\Permission; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Auth\SessionGuard;
use DB;

class UserController extends Controller
{
    use DatabaseFunctions;
    use Permission;

    public function __construct(){
        $this->__dbConstruct("users");
    }

    public function index(){
        return User::getUsersWithRoles();
    }

    public function register(Request $request){
        $userExists = $this->FindByInsertedOneProperty("email", $request->email);

        if(!$userExists){
            try{
                $user = new User;

                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = $request->password;

                $user->save();

                $token = JWTAuth::fromUser($user);

                return response()->json(["message" => "Usuário adicionado com sucesso!", "token" => $token]);
            } catch(Exception $e){
                return $e->getMessage();
            }
        }

        return response()->json(["message" => "Já existe um usuário com este e-mail."]);
    }

    public function update(Request $request, int $id){
        $userWithInsertedIDExists = $this->FindByInsertedOneProperty("id", $id);
        $userWithInsertedEmailOrNameExists = $this->FindByInsertedProperties(["name", "email"], [$request->name, $request->email]);

        if(!$userWithInsertedEmailOrNameExists){
            return response()->json(["message" => "Já existe um usuário com este nome ou e-mail."]);
        }

        DB::table("users as u")
              ->where("u.id", $id)
              ->update([
                "u.name" => $request->name,
                "u.email" => $request->email,
                "u.password" => bcrypt($request->password)
              ]);

        if(count($request->roles) >= 1){
            $this->__permissionConstruct("users_roles");
            $this->setRoles($request->roles, $userWithInsertedIDExists);
        }
    
        return response()->json(["message" => "Usuário atualizado com sucesso!"]);
    }

    public function destroy($id){
        $userWithInsertedIDExists = $this->FindByInsertedOneProperty("id", $id);

        if($userWithInsertedIDExists){
            $this->delete();

            return response()->json(["message" => "Usuário deletado com sucesso!"]);
        }

        return response()->json(["message" => "Não existe um usuário com este ID"]);
    }

    public function show(int $id){
        $user = $this->FindByInsertedOneProperty("id", $id);

        return response()->json(["users" => $user]);
    }

    public function login(Request $request){
        $adminUsers = $this->adminUsers;

        if(!Auth::attempt(["email" => $request->email, "password" => $request->password])){
            return response()->json(["message" => "E-mail ou senha incorretos."]);
        }

        $user = Auth::user();

        foreach($adminUsers as $adminUser){
            if($request["email"] === $adminUser["email"] && $request["password"] === $adminUser["password"]){
                $token = JWTAuth::fromUser($user, ["role" => "admin"]);

                return response()->json(["message" => "Administrador logado com sucesso!", "token" => $token]);
            }
        }

        $token = JWTAuth::fromUser($user);

        return response()->json(["message" => "Usuário logado com sucesso!", "token" => $token]);
    }

    public function logout(Request $request){
        Auth::logout();

        return response()->json(["message" => "Usuário deslogado com sucesso!"]);
    }
}
