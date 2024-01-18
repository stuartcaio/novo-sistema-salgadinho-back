<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\Role;
use JWTAuth;

class ValidateUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userByToken = JWTAuth::parseToken()->authenticate();
        $user = User::getUsersWithRoles()->where("id", $userByToken->id)->first();
        $resource = explode("/", $request->path())[0];
        $action = $request->method();
        $userHasPermission = [];

        if(!empty($user)){
            foreach($user->roles as $userRole){
                $role = Role::getRolesWithPermissions()->where("id", $userRole->id)->first();

                foreach($role->permissions as $permission){
                    if($permission->resource === $resource && $permission->action === $action){
                        array_push($userHasPermission, $user);
                    }
                }
            }

            if(count($userHasPermission) >= 1){
                return $next($request);
            }

            return response()->json(["message" => "O administrador inserido não está cadastro no sistema.", "status" => 403]);
        }

        return response()->json(["message" => "O usuário inserido não está cadastrado no sistema.", "status" => 401]);
    }
}
