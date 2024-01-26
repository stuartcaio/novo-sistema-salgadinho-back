<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Laravel\Sanctum\HasApiTokens;
use DB;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public static function getRoles($id){
        $roles = DB::table("roles")
                   ->select([
                    "roles.id",
                    "roles.name",
                    "roles.description"
                   ])
                   ->join("users_roles", "roles.id", "=", "users_roles.role_id")
                   ->join("users", "users.id", "=", "users_roles.user_id")
                   ->where("users.id", $id)
                   ->get();

        return $roles;
    }

    public static function getUsers(){
        return DB::table("users")
                 ->select(
                    "id",
                    "name",
                    "email",
                    "password"
                  )
                  ->get();
    }

    public static function getUsersWithRoles(){
        $users = User::getUsers();

        foreach($users as $user){
            $usersModel = new self();
            $user->roles = $usersModel->getRoles($user->id);
        }

        return $users;
    }
}
