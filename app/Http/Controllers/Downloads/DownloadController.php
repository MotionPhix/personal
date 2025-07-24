<?php

namespace App\Http\Controllers\Downloads;

use App\Http\Controllers\Controller;
use App\Http\Requests\Downloads\StoreDownloadRequest;
use App\Http\Requests\Downloads\UpdateDownloadRequest;
use App\Http\Resources\DownloadResource;
use App\Models\Download;
use App\Services\DownloadService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadController extends Controller
{
  protected DownloadService $downloadService;

  public function __construct(DownloadService $downloadService)
  {
    $this->downloadService = $downloadService;
  }

  /**
   * Display a listing of downloads for public view.
   */
  public function publicIndex(Request $request): Response
  {
    $downloads = $this->downloadService->getPublicDownloads($request);
    $categories = $this->downloadService->getCategories(true);
    $fileTypes = $this->downloadService->getFileTypes(true);
    $brands = $this->downloadService->getBrands(true);
    $featuredDownloads = $this->downloadService->getFeaturedDownloads();
    $popularDownloads = $this->downloadService->getPopularDownloads();
    $recentDownloads = $this->downloadService->getRecentDownloads();

    return Inertia::render('downloads/Index', [
      'downloads' => DownloadResource::collection($downloads),
      'featured_downloads' => DownloadResource::collection($featuredDownloads),
      'popular_downloads' => DownloadResource::collection($popularDownloads),
      'recent_downloads' => DownloadResource::collection($recentDownloads),
      'filters' => [
        'search' => $request->get('search', ''),
        'category' => $request->get('category', ''),
        'file_type' => $request->get('file_type', ''),
        'brand' => $request->get('brand', ''),
        'sort_by' => $request->get('sort_by', 'sort_order'),
        'sort_direction' => $request->get('sort_direction', 'asc'),
      ],
      'options' => [
        'categories' => $categories,
        'file_types' => $fileTypes,
        'brands' => $brands,
      ],
      'stats' => [
        'total_downloads' => $downloads->total(),
        'total_files' => $featuredDownloads->count() + $popularDownloads->count() + $recentDownloads->count(),
      ]
    ]);
  }

  /**
   * Display a listing of downloads.
   */
  public function index(Request $request): Response
  {
    $downloads = $this->downloadService->getAdminDownloads($request);
    $stats = $this->downloadService->getDownloadStats();
    $categories = $this->downloadService->getCategories();
    $fileTypes = $this->downloadService->getFileTypes();
    $brands = $this->downloadService->getBrands();

    return Inertia::render('admin/downloads/Index', [
      'downloads' => $downloads,
      'stats' => $stats,
      'filters' => [
        'search' => $request->get('search', ''),
        'category' => $request->get('category', ''),
        'file_type' => $request->get('file_type', ''),
        'brand' => $request->get('brand', ''),
        'featured' => $request->get('featured', ''),
        'public' => $request->get('public', ''),
        'sort_by' => $request->get('sort_by', 'sort_order'),
        'sort_direction' => $request->get('sort_direction', 'asc'),
      ],
      'options' => [
        'categories' => $categories,
        'file_types' => $fileTypes,
        'brands' => $brands,
      ],
    ]);
  }

  /**
   * Show the form for creating a new download.
   */
  public function create(): Response
  {
    $categories = $this->downloadService->getCategories();
    $fileTypes = $this->downloadService->getFileTypes();
    $brands = $this->downloadService->getBrands();

    return Inertia::render('admin/downloads/Create', [
      'options' => [
        'categories' => $categories,
        'file_types' => $fileTypes,
        'brands' => $brands,
      ],
    ]);
  }

  /**
   * Store a newly created download.
   */
  public function store(StoreDownloadRequest $request): RedirectResponse
  {
    try {
      // Create the download
      $download = $this->downloadService->createDownload($request->validated());

      // Handle file uploads
      $this->handleFileUploads($request, $download);

      return redirect()
        ->route('admin.downloads.show', $download)
        ->with('notify', [
          'type' => 'success',
          'title' => 'Download Created',
          'message' => 'Download has been successfully created!'
        ]);

    } catch (\Exception $e) {
      Log::error('Failed to create download', [
        'error' => $e->getMessage(),
        'request_data' => $request->validated()
      ]);

      return back()
        ->withInput()
        ->with('notify', [
          'type' => 'error',
          'title' => 'Error',
          'message' => 'Failed to create download: ' . $e->getMessage()
        ]);
    }
  }

  /**
   * Display the specified download.
   */
  public function show(Download $download): Response
  {
    $download = $this->downloadService->getDownload($download, ['media']);

    // Get download statistics
    $stats = $this->getDownloadAnalytics($download);

    // Get related downloads
    $relatedDownloads = $this->getRelatedDownloads($download);

    return Inertia::render('admin/downloads/Show', [
      'download' => new DownloadResource($download),
      'stats' => $stats,
      'related_downloads' => DownloadResource::collection($relatedDownloads),
    ]);
  }

  /**
   * Show the form for editing the specified download.
   */
  public function edit(Download $download): Response
  {
    $download = $this->downloadService->getDownload($download, ['media']);
    $categories = $this->downloadService->getCategories();
    $fileTypes = $this->downloadService->getFileTypes();
    $brands = $this->downloadService->getBrands();

    return Inertia::render('admin/downloads/Edit', [
      'download' => $download,
      'options' => [
        'categories' => $categories,
        'file_types' => $fileTypes,
        'brands' => $brands,
      ],
    ]);
  }

  /**
   * Update the specified download.
   */
  public function update(UpdateDownloadRequest $request, Download $download): RedirectResponse
  {
    try {
      // Update download data
      $this->downloadService->updateDownload($download, $request->validated());

      // Handle file uploads
      $this->handleFileUploads($request, $download);

      return redirect()
        ->route('admin.downloads.show', $download)
        ->with('notify', [
          'type' => 'success',
          'title' => 'Download Updated',
          'message' => 'Download has been successfully updated!'
        ]);

    } catch (\Exception $e) {
      Log::error('Failed to update download', [
        'download_id' => $download->id,
        'error' => $e->getMessage(),
        'request_data' => $request->validated()
      ]);

      return back()
        ->withInput()
        ->with('notify', [
          'type' => 'error',
          'title' => 'Error',
          'message' => 'Failed to update download: ' . $e->getMessage()
        ]);
    }
  }

  /**
   * Remove the specified download.
   */
  public function destroy(Download $download): RedirectResponse
  {
    try {
      $this->downloadService->deleteDownload($download);

      return redirect()
        ->route('admin.downloads.index')
        ->with('notify', [
          'type' => 'success',
          'title' => 'Download Deleted',
          'message' => 'Download has been successfully deleted!'
        ]);

    } catch (\Exception $e) {
      Log::error('Failed to delete download', [
        'download_id' => $download->id,
        'error' => $e->getMessage()
      ]);

      return back()
        ->with('notify', [
          'type' => 'error',
          'title' => 'Error',
          'message' => 'Failed to delete download: ' . $e->getMessage()
        ]);
    }
  }

  /**
   * Show the request fix form.
   */
  public function publicRequestFix(): Response
  {
    return Inertia::render('downloads/RequestFix');
  }

  /**
   * Handle file upload for fixing.
   */
  public function publicUploadFile(Request $request): JsonResponse
  {
    $request->validate([
      'file' => 'required|file|max:10240', // 10MB max
      'description' => 'required|string|max:1000',
      'contact_email' => 'required|email',
    ]);

    try {
      // Store the uploaded file temporarily
      $file = $request->file('file');
      $filename = time() . '_' . $file->getClientOriginalName();
      $path = $file->storeAs('fix-requests', $filename, 'public');

      // Log the request
      Log::info('Fix request uploaded', [
        'filename' => $filename,
        'description' => $request->description,
        'contact_email' => $request->contact_email,
        'ip_address' => $request->ip(),
      ]);

      return response()->json([
        'success' => true,
        'message' => 'File uploaded successfully! We will review your request and get back to you.',
      ]);

    } catch (\Exception $e) {
      Log::error('Fix request upload failed', [
        'error' => $e->getMessage(),
        'request_data' => $request->all()
      ]);

      return response()->json([
        'success' => false,
        'message' => 'Upload failed: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Download the file and increment download count.
   */
  public function download(Download $download): BinaryFileResponse|JsonResponse
  {
    try {
      // Check if download is public or user has permission
      if (!$download->is_public && !auth()->check()) {
        return response()->json([
          'success' => false,
          'message' => 'This download is not publicly available.'
        ], 403);
      }

      // Process download and get file URL
      $fileUrl = $this->downloadService->processDownload($download);

      // Get the media file
      $media = $download->getFirstMedia('file');

      if (!$media || !file_exists($media->getPath())) {
        return response()->json([
          'success' => false,
          'message' => 'Download file not found.'
        ], 404);
      }

      // Log the download
      Log::info('File downloaded', [
        'download_id' => $download->id,
        'download_title' => $download->title,
        'user_id' => auth()->id(),
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent()
      ]);

      // Return file download response
      return response()->download(
        $media->getPath(),
        $media->file_name,
        [
          'Content-Type' => $media->mime_type,
          'Content-Disposition' => 'attachment; filename="' . $media->file_name . '"'
        ]
      );

    } catch (\Exception $e) {
      Log::error('Download failed', [
        'download_id' => $download->id,
        'error' => $e->getMessage(),
        'user_id' => auth()->id()
      ]);

      return response()->json([
        'success' => false,
        'message' => 'Download failed: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Bulk update downloads.
   */
  public function bulkUpdate(Request $request): JsonResponse
  {
    $request->validate([
      'ids' => 'required|array',
      'ids.*' => 'exists:downloads,id',
      'action' => 'required|string|in:delete,feature,unfeature,publish,unpublish',
    ]);

    try {
      $ids = $request->input('ids');
      $action = $request->input('action');

      switch ($action) {
        case 'delete':
          $count = $this->downloadService->bulkDeleteDownloads($ids);
          break;
        case 'feature':
          $count = $this->downloadService->bulkUpdateDownloads($ids, ['is_featured' => true]);
          break;
        case 'unfeature':
          $count = $this->downloadService->bulkUpdateDownloads($ids, ['is_featured' => false]);
          break;
        case 'publish':
          $count = $this->downloadService->bulkUpdateDownloads($ids, ['is_public' => true]);
          break;
        case 'unpublish':
          $count = $this->downloadService->bulkUpdateDownloads($ids, ['is_public' => false]);
          break;
        default:
          throw new \Exception('Invalid action');
      }

      return response()->json([
        'success' => true,
        'message' => "Successfully {$action}d {$count} downloads",
        'count' => $count
      ]);

    } catch (\Exception $e) {
      Log::error('Bulk update failed', [
        'action' => $request->input('action'),
        'ids' => $request->input('ids'),
        'error' => $e->getMessage()
      ]);

      return response()->json([
        'success' => false,
        'message' => 'Bulk update failed: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Reorder downloads.
   */
  public function reorder(Request $request): JsonResponse
  {
    $request->validate([
      'downloads' => 'required|array',
      'downloads.*.id' => 'required|exists:downloads,id',
      'downloads.*.sort_order' => 'required|integer|min:0',
    ]);

    try {
      $this->downloadService->reorderDownloads($request->input('downloads'));

      return response()->json([
        'success' => true,
        'message' => 'Downloads reordered successfully'
      ]);

    } catch (\Exception $e) {
      Log::error('Reorder failed', [
        'downloads' => $request->input('downloads'),
        'error' => $e->getMessage()
      ]);

      return response()->json([
        'success' => false,
        'message' => 'Reorder failed: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Get download statistics.
   */
  public function stats(): JsonResponse
  {
    try {
      $stats = $this->downloadService->getDownloadStats();

      return response()->json([
        'success' => true,
        'data' => $stats
      ]);

    } catch (\Exception $e) {
      Log::error('Failed to get stats', [
        'error' => $e->getMessage()
      ]);

      return response()->json([
        'success' => false,
        'message' => 'Failed to get statistics: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Export downloads data.
   */
  public function export(Request $request): BinaryFileResponse|JsonResponse
  {
    try {
      $format = $request->get('format', 'csv');

      if (!in_array($format, ['csv', 'xlsx', 'json'])) {
        return response()->json([
          'success' => false,
          'message' => 'Invalid export format. Supported formats: csv, xlsx, json'
        ], 400);
      }

      $filePath = $this->downloadService->exportDownloads($format, $request->all());

      return response()->download($filePath)->deleteFileAfterSend();

    } catch (\Exception $e) {
      Log::error('Export failed', [
        'format' => $request->get('format'),
        'error' => $e->getMessage()
      ]);

      return response()->json([
        'success' => false,
        'message' => 'Export failed: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Duplicate a download.
   */
  public function duplicate(Download $download): RedirectResponse
  {
    try {
      $duplicatedDownload = $this->downloadService->duplicateDownload($download);

      return redirect()
        ->route('admin.downloads.edit', $duplicatedDownload)
        ->with('notify', [
          'type' => 'success',
          'title' => 'Download Duplicated',
          'message' => 'Download has been successfully duplicated!'
        ]);

    } catch (\Exception $e) {
      Log::error('Failed to duplicate download', [
        'download_id' => $download->id,
        'error' => $e->getMessage()
      ]);

      return back()
        ->with('notify', [
          'type' => 'error',
          'title' => 'Error',
          'message' => 'Failed to duplicate download: ' . $e->getMessage()
        ]);
    }
  }

  /**
   * Handle file uploads for downloads.
   */
  private function handleFileUploads(Request $request, Download $download): void
  {
    $posterFile = $request->file('poster_image');
    $downloadFile = $request->file('download_file');

    if ($posterFile || $downloadFile) {
      $this->downloadService->handleFileUploads(
        $download,
        $posterFile,
        $downloadFile
      );
    }
  }

  /**
   * Get analytics data for a specific download.
   */
  private function getDownloadAnalytics(Download $download): array
  {
    // In a real application, you would fetch this from a downloads log table
    // For now, we'll generate some realistic sample data

    $now = Carbon::now();
    $weekAgo = $now->copy()->subWeek();
    $monthAgo = $now->copy()->subMonth();

    // Simulate download counts (in real app, query from download_logs table)
    $downloadsToday = rand(0, 10);
    $downloadsThisWeek = $download->download_count > 0 ? rand($downloadsToday, min($download->download_count, 50)) : 0;
    $downloadsThisMonth = $download->download_count > 0 ? rand($downloadsThisWeek, $download->download_count) : 0;

    // Calculate trend
    $previousWeekDownloads = rand(0, $downloadsThisWeek);
    $trendPercentage = $previousWeekDownloads > 0
      ? round((($downloadsThisWeek - $previousWeekDownloads) / $previousWeekDownloads) * 100)
      : ($downloadsThisWeek > 0 ? 100 : 0);

    $trend = $trendPercentage > 5 ? 'up' : ($trendPercentage < -5 ? 'down' : 'stable');

    // Generate recent download activity
    $recentDownloads = [];
    for ($i = 6; $i >= 0; $i--) {
      $date = $now->copy()->subDays($i);
      $count = $i === 0 ? $downloadsToday : rand(0, 8);
      $recentDownloads[] = [
        'date' => $date->toDateString(),
        'count' => $count,
        'formatted_date' => $date->format('M j'),
      ];
    }

    return [
      'total_downloads' => $download->download_count,
      'downloads_today' => $downloadsToday,
      'downloads_this_week' => $downloadsThisWeek,
      'downloads_this_month' => $downloadsThisMonth,
      'peak_download_day' => $now->copy()->subDays(rand(1, 30))->toDateString(),
      'avg_downloads_per_day' => $download->download_count > 0
        ? round($download->download_count / max(1, $download->created_at->diffInDays($now)))
        : 0,
      'download_trend' => $trend,
      'trend_percentage' => abs($trendPercentage),
      'recent_downloads' => $recentDownloads,
      'growth_data' => [
        'labels' => collect($recentDownloads)->pluck('formatted_date')->toArray(),
        'data' => collect($recentDownloads)->pluck('count')->toArray(),
      ],
    ];
  }

  /**
   * Get related downloads based on category and tags.
   */
  private function getRelatedDownloads(Download $download, int $limit = 6): \Illuminate\Database\Eloquent\Collection
  {
    $query = Download::where('id', '!=', $download->id)
      ->where('is_public', true)
      ->with('media');

    // First, try to find downloads in the same category
    if ($download->category) {
      $categoryMatches = $query->clone()
        ->where('category', $download->category)
        ->limit($limit)
        ->get();

      if ($categoryMatches->count() >= $limit) {
        return $categoryMatches;
      }
    }

    // If not enough category matches, find by similar tags
    if (!empty($download->tags)) {
      $tagMatches = $query->clone()
        ->where(function ($q) use ($download) {
          foreach ($download->tags as $tag) {
            $q->orWhereJsonContains('tags', $tag);
          }
        })
        ->limit($limit)
        ->get();

      if ($tagMatches->count() >= $limit) {
        return $tagMatches;
      }
    }

    // If still not enough, get recent downloads from same brand
    if ($download->brand) {
      $brandMatches = $query->clone()
        ->where('brand', $download->brand)
        ->orderBy('created_at', 'desc')
        ->limit($limit)
        ->get();

      if ($brandMatches->count() >= $limit) {
        return $brandMatches;
      }
    }

    // Finally, just get recent popular downloads
    return $query->clone()
      ->orderBy('download_count', 'desc')
      ->orderBy('created_at', 'desc')
      ->limit($limit)
      ->get();
  }
}
