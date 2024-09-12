<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
  /**
   * Display the all available projects.
   */
  public function index(): Response
  {
    $projects = Project::all();

    return Inertia::render('Projects/Index', [
      'projects' => $projects,
    ]);
  }

  /**
   * Display the available projects in admin for crud.
   */
  public function listing(): Response
  {
    $projects = Project::with('images', 'customer')->get();

    return Inertia::render('Admin/Projects/Index', [
      'projects' => $projects,
    ]);
  }

  /**
   * Display the selected bucket.
   */
  public function show(Project $project): Response
  {
    return Inertia::render('Projects/Show', [
      'project' => $project->load('customer', 'images'),
    ]);
  }

  /**
   * Display the selected bucket.
   */
  public function detail(Project $project): Response
  {
    return Inertia::render('Admin/Projects/Show', [
      'project' => $project->load('customer', 'images'),
    ]);
  }

  /**
   * Create a new instance of the bucket.
   */
  public function create(Customer $customer = null): Response
  {
    $project = [
      'id' => '',
      'pid' => '',
      'name' => '',
      'description' => '',
      'production' => '',
      'poster' => '',
      'customer_id' => $customer->cid ?? '',
      'images' => [],
    ];

    return Inertia::render('Admin/Projects/Form', [
      'project' => $project,
    ]);
  }

  /**
   * Edit the selected bucket.
   */
  public function edit(Project $project): Response
  {
    $customerId = Customer::where('id', $project->customer_id)->first()->cid;
    $project->customer_id = $customerId;
    $project->poster = url($project->poster); // Ensure poster is a full URL
    $project->images = $project->images->map(function ($image) {
      $image->src = url($image->src); // Ensure image src is a full URL
      return $image;
    });

    return Inertia::render('Admin/Projects/Form', [
      'project' => $project,
    ]);
  }

  /**
   * Display the available projects in admin for crud.
   */
  public function store(Request $request)
  {
    $customer = Customer::where('cid', $request->customer_id)->first();
    $request->merge(['customer_id' => $customer->id]);

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'production' => 'required|date',
      'customer_id' => 'required|exists:customers,id',
      'description' => 'nullable|string',
      'poster' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    // Handle poster upload
    if ($request->hasFile('poster')) {

      $poster = $request->file('poster');

      // Generate a unique filename for the poster
      $posterFileName = uniqid() . '.' . $poster->getClientOriginalExtension();

      // Move the poster to the "public/poster" directory
      $poster->move(public_path('poster'), $posterFileName);

      // Generate the relative path (without 'public')
      $relativePosterPath = '/poster/' . $posterFileName;
    }

    $project = Project::create([
      'name' => $validated['name'],
      'production' => $validated['production'],
      'customer_id' => $customer->id,
      'description' => $validated['description'] ?? null,
      'poster' => $relativePosterPath,
    ]);

    // Handle multiple image uploads
    if ($request->hasFile('images')) {

      foreach ($request->file('images') as $image) {

        $imageFileName = uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('bucket'), $imageFileName);

        // Generate the relative path (without 'public')
        $relativeImagePath = '/bucket/' . $imageFileName;

        // Get the dimensions of each image
        $imageSize = getimagesize(public_path($relativeImagePath));
        $imageDimensions = $imageSize ? "{$imageSize[0]}x{$imageSize[1]}" : null;

        // Save the image in the polymorphic relation using morphMany
        $project->images()->create([
          'src' => $relativeImagePath, // Path of the uploaded image
          'mime_type' => $image->getClientMimeType(), // MIME type of the file
          'size' => $imageDimensions, // Save image dimensions as widthxheight
        ]);
      }
    }

    session()->flash('notify', [
      'type' => 'success',
      'message' => 'Project has been successfully added!'
    ]);

    return redirect()->route('auth.projects.index');
  }

  /**
   * Update the edited bucket.
   */
  public function update(Request $request, Project $project)
  {
    $customer = Customer::where('cid', $request->customer_id)->first();
    $request->merge(['customer_id' => $customer->id]);

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'production' => 'required|date',
      'customer_id' => 'required|exists:customers,id',
      'description' => 'nullable|string',
      'poster' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    // Handle poster upload
    if ($request->hasFile('poster')) {
      // $posterPath = $request->file('poster')->store('poster', 'public');

      $poster = $request->file('poster');

      // Generate a unique filename for the poster
      $posterFileName = uniqid() . '.' . $poster->getClientOriginalExtension();

      // Move the poster to the "public/poster" directory
      $poster->move(public_path('poster'), $posterFileName);

      // Generate the relative path (without 'public')
      $relativePosterPath = '/poster/' . $posterFileName;
    }

    $project = Project::create([
      'name' => $validated['name'],
      'production' => $validated['production'],
      'customer_id' => $customer->id,
      'description' => $validated['description'] ?? null,
      'poster' => $relativePosterPath,
    ]);

    // Handle multiple image uploads
    if ($request->hasFile('images')) {

      foreach ($request->file('images') as $image) {

        $imageFileName = uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('bucket'), $imageFileName);

        // Generate the relative path (without 'public')
        $relativeImagePath = '/bucket/' . $imageFileName;

        // Get the dimensions of each image
        $imageSize = getimagesize(public_path($relativeImagePath));
        $imageDimensions = $imageSize ? "{$imageSize[0]}x{$imageSize[1]}" : null;

        // Save the image in the polymorphic relation using morphMany
        $project->images()->create([
          'src' => $relativeImagePath,             // Path of the uploaded image
          'mime_type' => $image->getClientMimeType(), // MIME type of the file
          'size' => $imageDimensions, // Save image dimensions as widthxheight
        ]);
      }
    }

    return redirect()->route('auth.projects.index');
  }

  // Method to delete a bucket or an image
  public function destroy(Project $project, $image = null)
  {
    // Check if the image parameter is provided
    if ($image) {
      // If the image is provided, delete the specific image
      $imageRecord = $project->images()->where('id', $image)->first();

      if ($imageRecord) {
        // Delete the image file if stored locally
        if (file_exists(public_path($imageRecord->src))) {
          unlink(public_path($imageRecord->src));
        }

        // Delete the image record from the database
        $imageRecord->delete();

        session()->flash('notify', [
          'type' => 'success',
          'title' => 'Bravo',
          'message' => 'Image was deleted successfully!'
        ]);

        return redirect()->back();
      }

      session()->flash('notify', [
        'type' => 'danger',
        'title' => 'Failed',
        'message' => 'Image could not be found!'
      ]);

      return redirect()->back();
    }

    // If no image is provided, delete the entire bucket along with all its images
    foreach ($project->images as $imageRecord) {
      // Delete each image file if stored locally
      if (file_exists(public_path($imageRecord->src))) {
        unlink(public_path($imageRecord->src));
      }
    }

    // Delete the bucket's poster if it exists
    if ($project->poster && file_exists(public_path($project->poster))) {
      unlink(public_path($project->poster));
    }

    // Delete the bucket and its images
    $project->images()->delete();

    $project->delete();

    session()->flash('notify', [
      'type' => 'success',
      'title' => 'Done',
      'message' => 'Project and its images were deleted successfully!'
    ]);

    return redirect()->route('auth.projects.index');
  }
}
