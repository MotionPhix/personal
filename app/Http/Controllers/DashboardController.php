<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Logo;
use App\Models\Project;
use App\Models\Subscriber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request)
  {
    $customersCount = Customer::count();
    $projectsCount = Project::count();
    $downloadsCount = Logo::count();
    $subscribersCount = Subscriber::where('subscribed', true)->count();

    $latestCustomers = Customer::latest()
      ->select(['cid', 'first_name', 'last_name', 'company_name'])
      ->take(5)->get();

    $latestProjects = Project::with('customer:id,first_name,last_name')
      ->select(['id', 'customer_id', 'name', 'production'])
      ->latest()->take(5)->get()
      ->transform(function ($project) {
        return [
          'id' => $project->id,
          'name' => $project->name,
          'completion_date' => $project->production->format('M, Y'),
          'customer' => [
            'id' => $project->customer_id,
            'name' => $project->customer->first_name . ' ' . $project->customer->last_name,
          ],
        ];
      });

    // subscribers trend for the last 30 days
    $subscribersTrend = Subscriber::selectRaw('DATE(created_at) as date, count(*) as count')
      ->groupBy('date')
      ->orderBy('date')
      ->take(30)
      ->get();

    $trends = Subscriber::selectRaw('DATE(created_at) as date,
      COUNT(CASE WHEN subscribed THEN 1 END) as subscribed_count,
      COUNT(CASE WHEN NOT subscribed THEN 1 END) as unsubscribed_count')
      ->where('created_at', '>=', Carbon::now()->subDays(30))
      ->groupBy('date')
      ->orderBy('date')
      ->take(30)
      ->get();

    $formattedTrends = [];

    foreach ($trends as $item) {

      $formattedTrends[] = [
        'date' => $item->date,
        'value' => $item->subscribed_count,
        'category' => 'subscribed',
      ];

      $formattedTrends[] = [
        'date' => $item->date,
        'value' => $item->unsubscribed_count,
        'category' => 'unsubscribed',
      ];
    }

    // Fetch customer counts for the last two months
    $currentMonthCustomers = Customer::whereBetween('created_at', [
      now()->startOfMonth(),
      now()->endOfMonth()
    ])->count();

    $previousMonthCustomers = Customer::whereBetween('created_at', [
      now()->subMonth()->startOfMonth(),
      now()->subMonth()->endOfMonth()
    ])->count();

    // Calculate percentage change
    $customersPercentageChange = $this->calculatePercentageChange($previousMonthCustomers, $currentMonthCustomers);

    // Same for projects
    $currentMonthProjects = Project::whereBetween('created_at', [
      now()->startOfMonth(),
      now()->endOfMonth()
    ])->count();

    $previousMonthProjects = Project::whereBetween('created_at', [
      now()->subMonth()->startOfMonth(),
      now()->subMonth()->endOfMonth()
    ])->count();

    $projectsPercentageChange = $this->calculatePercentageChange($previousMonthProjects, $currentMonthProjects);

    // Same for logos
    $currentMonthLogos = Logo::whereBetween('created_at', [
      now()->startOfMonth(),
      now()->endOfMonth()
    ])->count();

    $previousMonthLogos = Logo::whereBetween('created_at', [
      now()->subMonth()->startOfMonth(),
      now()->subMonth()->endOfMonth()
    ])->count();

    $logosPercentageChange = $this->calculatePercentageChange($previousMonthLogos, $currentMonthLogos);

    return Inertia::render('admin/Dashboard', [
      'customersCount' => $customersCount,
      'projectsCount' => $projectsCount,
      'downloadsCount' => $downloadsCount,
      'subscribersCount' => $subscribersCount,
      'latestCustomers' => $latestCustomers,
      'latestProjects' => $latestProjects,
      'subscribersTrend' => $subscribersTrend,
      'trends' => $formattedTrends,
      'customersPercentageChange' => $customersPercentageChange,
      'logosPercentageChange' => $logosPercentageChange,
      'projectsPercentageChange' => $projectsPercentageChange
    ]);
  }

  private function calculatePercentageChange($previousValue, $currentValue)
  {
    if ($previousValue == 0 && $currentValue == 0) {
      return 0; // No change if both are zero
    }

    if ($previousValue == 0) {
      return 100; // Full increase from 0
    }

    return (($currentValue - $previousValue) / $previousValue) * 100;
  }
}
