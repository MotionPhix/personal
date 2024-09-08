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
    $projects = Project::with('images')->get();

    return Inertia::render('Admin/Projects/Index', [
      'projects' => $projects,
    ]);
  }

  /**
   * Display the selected project.
   */
  public function show(Project $project): Response
  {
    return Inertia::render('Projects/Show', [
      'project' => $project->load('customer', 'images'),
    ]);
  }

  /**
   * Create a new instance of the project.
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
      'customer_id' => $customer->id ?? '',
      'images' => [],
    ];

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
      // $posterPath = $request->file('poster')->store('posters', 'public');

      $poster = $request->file('poster');

      // Generate a unique filename for the poster
      $posterFileName = uniqid() . '.' . $poster->getClientOriginalExtension();

      // Move the poster to the "public/posters" directory
      $poster->move(public_path('posters'), $posterFileName);

      // Generate the relative path (without 'public')
      $relativePosterPath = '/posters/' . $posterFileName;
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
        $image->move(public_path('project/images'), $imageFileName);

        // Generate the relative path (without 'public')
        $relativeImagePath = '/project/images/' . $imageFileName;

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
}
