<?php
    namespace App\Http\Controllers\Traits;

    use App\Http\Controllers\Traits\DatabaseFunctions;
    use Illuminate\Http\Request;
    use DB;

    trait Permission{
        use DatabaseFunctions;

        protected function __permissionConstruct(string $table){
            $this->__dbConstruct($table);
        }

        private function setPermissions($permissions, $role){
            if($permissions){
                foreach($permissions as $permission){
                    $this->save(["permission_id" => $permission, "role_id" => $role->id]);
                }
            }
        }

        private function setRoles($roles, $user){
            if($roles){
                foreach($roles as $role){
                    $this->save(["role_id" => $role, "user_id" => $user->id]);
                }
            }
        }
    }
?>