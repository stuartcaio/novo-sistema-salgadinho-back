<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Permission extends Model
{
    use HasFactory;

    public function users(){
        return $this->belongsToMany(User::class, "roles_permissions");
    }

    public static function getPermissions(){
        $permissions = DB::table("permissions")
                         ->select(
                            "permissions.name",
                            "permissions.description",
                            "permissions.resource",
                            "permissions.action"
                         )
                         ->get();

        return $permissions;
    }
}
