<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Services\ProjectService;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    protected ProjectService $projectService;
    protected CustomerService $customerService;

    public function __construct(ProjectService $projectService, CustomerService $customerService)
    {
        $this->projectService = $projectService;
        $this->customerService = $customerService;
    }

    /**
     * Get public portfolio statistics.
     */
    public function __invoke(Request $request)
    {
        $stats = [
            'total_public_projects' => \App\Models\Project::public()->count(),
            'completed_projects' => \App\Models\Project::public()->completed()->count(),
            'featured_projects' => \App\Models\Project::public()->featured()->count(),
            'production_types' => $this->projectService->getProductionTypes(true),
            'categories' => $this->projectService->getCategories(true),
            'technologies' => $this->projectService->getTechnologies(),
            'years_active' => \App\Models\Project::public()
                ->whereNotNull('start_date')
                ->selectRaw('YEAR(start_date) as year')
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year'),
        ];

        return response()->json($stats);
    }
}
