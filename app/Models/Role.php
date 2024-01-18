<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Role extends Model
{
    use HasFactory;

    private static function getPermissions($id){
        $permissions = DB::table("permissions")
                         ->select([
                            "permissions.name",
                            "permissions.description",
                            "permissions.resource",
                            "permissions.action"
                         ])
                         ->join("roles_permissions", "permissions.id", "=", "roles_permissions.permission_id")
                         ->join("roles", "roles.id", "=", "roles_permissions.role_id")
                         ->where("roles.id", "=", $id)
                         ->get();
        
        return $permissions;
    }

    private static function getRoles(){
        $roles = DB::table("roles")
                   ->select([
                    "roles.id",
                    "roles.name",
                    "roles.description"
                   ])
                   ->get();

        return $roles;
    }

    public static function getRolesWithPermissions(){
        $roles = Role::getRoles();

        foreach($roles as $role){
            $rolesModel = new self();
            $role->permissions = $rolesModel->getPermissions($role->id);
        }

        return $roles;
    }
}
