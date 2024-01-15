<?php

namespace App\Livewire;

use App\Models\Report;
use Livewire\Component;
use App\Models\Percentage;

class Test extends Component
{

    public $startDate;
    public $endDate;
    public $previousStartDate;
    public $previousEndDate;

    public $salesTotal = 0;
    public $expensesTotal = 0;



    public function mount()
    {
        $this->startDate = now()->startOfMonth()->toDateString();
        $this->endDate = now()->endOfMonth()->toDateString();

        $this->getSalesWithinPeriod(); // Fetch sales total for the current month
        $this->getExpensesWithinPeriod();
    }


    public function updateDates()
    {
        $this->previousStartDate = $this->startDate;
        $this->previousEndDate = $this->endDate;
    }
    public function getSalesWithinPeriod()
    {
        if (!$this->startDate || !$this->endDate) {
            $this->salesTotal = 0;
            return;
        }

        $this->salesTotal = Report::whereDate('report_date', '>=', $this->startDate)
                                  ->whereDate('report_date', '<=', $this->endDate)
                                  ->sum('sales');
    }


    public function getExpensesWithinPeriod()
{
    if (!$this->startDate || !$this->endDate) {
        $this->expensesTotal = 0;
        return;
    }

    $this->expensesTotal = Percentage::whereDate('expense_date', '>=', $this->startDate)
                                    ->whereDate('expense_date', '<=', $this->endDate)
                                    ->sum('amount'); // Assuming 'expenses' is the column with the expenses data.
}

public function updated($propertyName)
    {
        if ($propertyName === 'startDate' || $propertyName === 'endDate') {
            $this->getSalesWithinPeriod();
            $this->getExpensesWithinPeriod();
        }
    }

    public function render()
    {
        return view('livewire.test');
    }
}
