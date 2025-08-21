<x-mail::message>
# New Quote Request

You have received a new quote request from **{{ $quote->name }}**.

## Contact Information
- **Name:** {{ $quote->name }}
- **Email:** {{ $quote->email }}
@if($quote->phone)
- **Phone:** {{ $quote->phone }}
@endif
@if($quote->company)
- **Company:** {{ $quote->company }}
@endif

## Project Details
- **Project Type:** {{ $quote->formatted_project_type }}
- **Budget Range:** {{ $quote->formatted_budget_range }}
- **Timeline:** {{ $quote->formatted_timeline }}

## Project Description
{{ $quote->description }}

@if($quote->goals)
## Project Goals
{{ $quote->goals }}
@endif

@if($quote->target_audience)
## Target Audience
{{ $quote->target_audience }}
@endif

@if($quote->additional_info)
## Additional Information
{{ $quote->additional_info }}
@endif

@if($files->count() > 0)
## Reference Files
The following files have been attached to this quote request:
@foreach($files as $file)
- {{ $file->name }} ({{ $file->human_readable_size }})
@endforeach
@endif

<x-mail::button :url="config('app.url') . '/admin/quotes/' . $quote->id">
View Quote Request
</x-mail::button>

Thanks,<br>
{{ config('app.name') }} Quote System
</x-mail::message>
