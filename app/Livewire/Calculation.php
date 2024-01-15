<?php

namespace App\Livewire;
use App\Models\Report;
use Livewire\Component;
use App\Models\Percentage;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;




class Calculation extends Component
{
    
    public $restaurantID;
    public $currentRestaurant;
    public $currentRestaurantName;
    public $food;
    public $alcohol;
    public $others;
    public $utilities;
    public $payroll;
    public $sales;
    public $expenses;
    public $totalFood;
    public $totalAlcohol;
    public $totalUtilities;
    public $totalOthers;
    public $totalPayroll;
    public $pFood;
    public $pAlcohol;
    public $pOthers;
    public $pUtilities;
    public $pPayroll;

    public $restaurantOptions;
    public $desiredRestaurantId='';
    public $number;

    public $startDate;
    public $endDate;
    public $previousStartDate;
    public $previousEndDate;

    




    public function mount()
    {
        $user = Auth::user();
        $this->restaurantOptions = $user->restaurants->pluck('name', 'id')->toArray();
        $url = request()->url();
            preg_match('/admin\/(\d+)\/p-calc/', $url, $matches);
            if (isset($matches[1])) {
                $this->number = $matches[1];
            }
            $this->startDate = now()->startOfMonth()->toDateString();
            $this->endDate = now()->endOfMonth()->toDateString();

            $this->foodPercentage();
            $this->alcoholPercentage();
            $this->payrollPercentage();
            $this->utilitiesPercentage();
            $this->othersPercentage();
            $this->currentRestaurant = $this->number;

            // Set the current restaurant name
            $this->currentRestaurantName = $this->getCurrentRestaurantName();

    }

    public function getCurrentRestaurantName()
{
    // Add logic to fetch the restaurant name based on $this->currentRestaurant
    // For example, if you have a Restaurant model:
    $restaurant = Restaurant::find($this->currentRestaurant);
    return $restaurant ? $restaurant->name : 'Unknown Restaurant';
}

    public function updateDates()
    {
        $this->previousStartDate = $this->startDate;
        $this->previousEndDate = $this->endDate;
    }

    public function calculatePercentage($type)
    {
        $currentRestaurantId = $this->number;

        $sales = Report::whereHas('restaurants', function ($query) use ($currentRestaurantId) {
            $query->where('restaurant_id', $currentRestaurantId);
        })
        ->whereDate('report_date', '>=', $this->startDate)
        ->whereDate('report_date', '<=', $this->endDate)
        ->sum('sales');
        $this->sales = $sales;



        $expenses = Percentage::whereHas('restaurants', function ($query) use ($currentRestaurantId) {
            $query->where('restaurant_id', $currentRestaurantId);
        })
        ->whereDate('expense_date', '>=', $this->startDate)
        ->whereDate('expense_date', '<=', $this->endDate)
        ->sum('amount');
        $this->expenses = $expenses;

        $percentage = Percentage::whereHas('restaurants', function ($query) use ($currentRestaurantId) {
            $query->where('restaurant_id', $currentRestaurantId);
        })
        ->whereDate('expense_date', '>=', $this->startDate)
        ->whereDate('expense_date', '<=', $this->endDate)
        ->where('type', $type)
        ->sum('amount');

        // Calculate the percentage (P) while handling division by zero
        $percentageValue = ($this->sales != 0) ? 100*$percentage / $this->sales : "Cannot divide by zero.";

        // Store the sum based on type
        if ($type === 'Food') {
            $this->food = $percentage;
            $this->totalFood = $percentage;
        } elseif ($type === 'Alcohol') {
            $this->alcohol = $percentage;
            $this->totalAlcohol = $percentage;
        } elseif ($type === 'Payroll') {
            $this->payroll = $percentage;
            $this->totalPayroll = $percentage;
        } elseif ($type === 'Utilities') {
            $this->utilities = $percentage;
            $this->totalUtilities = $percentage;
        } elseif ($type === 'Others') {
            $this->others = $percentage;
            $this->totalOthers = $percentage;
        }

        return $percentageValue;
    }

   
    



    public function foodPercentage()
    {
        $this->pFood = $this->calculatePercentage('Food');
    }

    public function alcoholPercentage()
    {
        $this->pAlcohol = $this->calculatePercentage('Alcohol');
    }

    public function payrollPercentage()
    {
        $this->pPayroll = $this->calculatePercentage('Payroll');
    }

    public function utilitiesPercentage()
    {
        $this->pUtilities = $this->calculatePercentage('Utilities');
    }

    public function othersPercentage()
    {
        $this->pOthers = $this->calculatePercentage('Others');
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'startDate' || $propertyName === 'endDate') {
            $this->fetchSalesAndExpenses();
        }
    }



    public function fetchSalesAndExpenses()
    {
        $currentRestaurantId = $this->number;

        // Fetch sales
        $this->sales = Report::whereHas('restaurants', function ($query) use ($currentRestaurantId) {
            $query->where('restaurant_id', $currentRestaurantId);
        })
        ->whereDate('report_date', '>=', $this->startDate)
        ->whereDate('report_date', '<=', $this->endDate)
        ->sum('sales');

        // Fetch expenses
        $this->expenses = Percentage::whereHas('restaurants', function ($query) use ($currentRestaurantId) {
            $query->where('restaurant_id', $currentRestaurantId);
        })
        ->whereDate('expense_date', '>=', $this->startDate)
        ->whereDate('expense_date', '<=', $this->endDate)
        ->sum('amount');

        // Update percentages
        $this->foodPercentage();
        $this->alcoholPercentage();
        $this->payrollPercentage();
        $this->utilitiesPercentage();
        $this->othersPercentage();

    }

    public function render()
    {
        $user = Auth::user();
        $role = $user->getRoleNames()->first(); // Assuming a user has only one role

        return view('livewire.calculation', ['role' => $role]);

         //return view('livewire.calculation');


    }

   
    
  



   

}


