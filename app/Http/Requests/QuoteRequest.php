<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'company' => ['nullable', 'string', 'max:255'],
            'project_type' => ['required', 'string', 'in:web_design,branding,photography,marketing,print_design'],
            'budget_range' => ['required', 'string', 'in:under_1000,1000_5000,5000_10000,10000_25000,over_25000,discuss'],
            'timeline' => ['required', 'string', 'in:asap,1_2_weeks,1_month,2_3_months,3_6_months,flexible'],
            'description' => ['required', 'string', 'min:10'],
            'goals' => ['nullable', 'string'],
            'target_audience' => ['nullable', 'string', 'max:255'],
            'additional_info' => ['nullable', 'string'],
            'files.*' => ['nullable', 'file', 'max:10240', 'mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,txt'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your full name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'project_type.required' => 'Please select a project type.',
            'project_type.in' => 'Please select a valid project type.',
            'budget_range.required' => 'Please select your budget range.',
            'budget_range.in' => 'Please select a valid budget range.',
            'timeline.required' => 'Please select your preferred timeline.',
            'timeline.in' => 'Please select a valid timeline.',
            'description.required' => 'Please provide a project description.',
            'description.min' => 'Project description must be at least 10 characters.',
            'files.*.max' => 'Each file must be smaller than 10MB.',
            'files.*.mimes' => 'Only images, PDFs, and document files are allowed.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'project_type' => 'project type',
            'budget_range' => 'budget range',
            'target_audience' => 'target audience',
            'additional_info' => 'additional information',
        ];
    }
}
