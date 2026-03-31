<?php

namespace App\Observers;

use App\Models\Income;
use App\Services\DashboardService;

class IncomeObserver
{
    public function created(Income $income): void
    {
        DashboardService::clearCache($income->user_id);
    }

    public function updated(Income $income): void
    {
        DashboardService::clearCache($income->user_id);
    }

    public function deleted(Income $income): void
    {
        DashboardService::clearCache($income->user_id);
    }
}
