<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Employee;
use App\Http\Models\Company;
use DB;

class Order extends Model
{
    use HasFactory;

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function company(): HasOne
    {
        return this->hasOne(Company::class);
    }

    public static function getOrders(){
        return DB::table("orders")
                 ->join("companies", "companies.id", "=", "orders.company_id")
                 ->join("employees", "employees.id", "=", "orders.employee_id")
                 ->select(
                    "orders.created_at as data",
                    "companies.name as comanyName",
                    "employees.name as employeeName"
                 )
                 ->get();
    }
}
