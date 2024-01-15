<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Order;
use DB;

class Employee extends Model
{
    use HasFactory;

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public static function getEmployees(){
        $employees = DB::table("employees")
                       ->select(
                            "id",
                            "name",
                            "dateOfBirth",
                            "dateOfSigning"
                        )
                       ->get();

        foreach($employees as $employee){
            $employee->orders = self::getOrders($employee->id);
        }

        return $employee;
    }

    private static function getOrders(int $employeeID){
        return DB::table("orders")
                 ->select(
                    "orders.created_at as data",
                    "companies.name"
                 )
                 ->join("companies", "companies.id", "=", "orders.company_id")
                 ->where("orders.employee_id", $employeeID)
                ->get();
    }
}
