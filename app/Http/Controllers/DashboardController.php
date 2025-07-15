<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
  protected DashboardService $dashboardService;

  public function __construct(DashboardService $dashboardService)
  {
    $this->dashboardService = $dashboardService;
  }

  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request)
  {
    $dashboardData = $this->dashboardService->getDashboardData();
    $notifications = $this->dashboardService->getNotifications();

    return Inertia::render('admin/Dashboard', array_merge($dashboardData, [
      'notifications' => $notifications,
    ]));
  }

  /**
   * Refresh dashboard data (clear cache).
   */
  public function refresh()
  {
    $this->dashboardService->clearCache();

    return redirect()->route('admin.dashboard')->with('success', 'Dashboard data refreshed successfully.');
  }
}
