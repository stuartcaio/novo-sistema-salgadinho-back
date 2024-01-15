<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Employee;
use App\Http\Models\Company;
use DB;

class Review extends Model
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

    public static function getReviews(){
        return DB::table("reviews")
                 ->join("companies", "companies.id", "=", "reviews.company_id")
                 ->join("employees", "employees.id", "=", "reviews.employee_id")
                 ->select(
                    "reviews.description",
                    "reviews.reviewValue",
                    "reviews.created_at as data",
                    "companies.name as comanyName",
                    "employees.name as employeeName"
                 )
                 ->get();
    }
}
