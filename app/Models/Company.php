<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Order;
use DB;

class Company extends Model
{
    use HasFactory;

    public function orders(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public static function getCompanies(){
        $companies = DB::table("companies")
                       ->select(
                            "id",
                            "name",
                            "address",
                       )
                       ->get();

        foreach($companies as $company){
            $calculatedAverageReview = self::calculateAverageReview($company->id);

            $company->averageReview = $calculatedAverageReview->reviewValue;
            $company->reviewNumbers = $calculatedAverageReview->reviewNumbers;
            $company->orders = self::getOrders($company->id);
        }
        
        return $company;
    }

    private static function calculateAverageReview(int $companyId){
        $averageReview = DB::table("reviews")
                                ->select(
                                    DB::raw("AVG(reviews.reviewValue) as reviewValue"),
                                    DB::raw("COUNT(*) as reviewNumbers")
                                )
                                ->where("reviews.company_id", $companyId)
                                ->get();
                                
        return $averageReview[0];
    }

    private static function getOrders(int $companyId){
        return DB::table("orders")
                 ->select(
                    "orders.created_at as data",
                    "employees.name"
                 )
                 ->join("employees", "employees.id", "=", "orders.employee_id")
                 ->where("orders.employee_id", $companyId)
                ->get();
    }
}
