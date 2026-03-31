<?php

namespace App\Observers;

use App\Models\Expense;
use App\Services\DashboardService;

class ExpenseObserver
{
    public function created(Expense $expense): void
    {
        DashboardService::clearCache($expense->user_id);
    }

    public function updated(Expense $expense): void
    {
        DashboardService::clearCache($expense->user_id);
    }

    public function deleted(Expense $expense): void
    {
        DashboardService::clearCache($expense->user_id);
    }
}
