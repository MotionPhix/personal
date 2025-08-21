<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuoteRequest;
use App\Mail\QuoteRequestMail;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class QuoteController extends Controller
{
    /**
     * Display the quote request form.
     */
    public function index(): Response
    {
        return Inertia::render('quote/Index');
    }

    /**
     * Store a new quote request.
     */
    public function store(QuoteRequest $request)
    {
        try {
            // Create the quote record
            $quote = Quote::create($request->validated());

            // Handle file uploads using Spatie Media Library
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $quote->addMediaFromRequest('files')
                        ->each(function ($fileAdder) {
                            $fileAdder->toMediaCollection('reference_files');
                        });
                }
            }

            // Send email notification to admin
            Mail::to(config('mail.admin_email', 'admin@ultrashots.com'))
                ->send(new QuoteRequestMail($quote));

            // Return success response
            return back()->with('success', 'Your quote request has been sent successfully! We\'ll get back to you within 24 hours.');

        } catch (\Exception $e) {
            return back()->with('error', 'There was an error sending your quote request. Please try again.');
        }
    }
}
