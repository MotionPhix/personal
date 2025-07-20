<?php

namespace App\Http\Controllers\Downloads;

use App\Http\Controllers\Controller;
use App\Http\Requests\Downloads\StoreDownloadRequest;
use App\Http\Requests\Downloads\UpdateDownloadRequest;
use App\Models\Download;
use App\Services\DownloadService;
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

    return Inertia::render('admin/downloads/Show', [
      'download' => $download,
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
}
