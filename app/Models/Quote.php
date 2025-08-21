<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Quote extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'project_type',
        'budget_range',
        'timeline',
        'description',
        'goals',
        'target_audience',
        'additional_info',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Define media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('reference_files')
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/webp',
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'text/plain',
            ]);
    }

    /**
     * Define media conversions
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300)
            ->sharpen(10)
            ->performOnCollections('reference_files')
            ->nonQueued();
    }

    /**
     * Get formatted project type
     */
    public function getFormattedProjectTypeAttribute(): string
    {
        return match($this->project_type) {
            'web_design' => 'Web Design',
            'branding' => 'Branding & Identity',
            'photography' => 'Photography',
            'marketing' => 'Digital Marketing',
            'print_design' => 'Print Design',
            default => ucfirst(str_replace('_', ' ', $this->project_type))
        };
    }

    /**
     * Get formatted budget range
     */
    public function getFormattedBudgetRangeAttribute(): string
    {
        return match($this->budget_range) {
            'under_1000' => 'Under $1,000',
            '1000_5000' => '$1,000 - $5,000',
            '5000_10000' => '$5,000 - $10,000',
            '10000_25000' => '$10,000 - $25,000',
            'over_25000' => 'Over $25,000',
            'discuss' => 'Let\'s discuss',
            default => $this->budget_range
        };
    }

    /**
     * Get formatted timeline
     */
    public function getFormattedTimelineAttribute(): string
    {
        return match($this->timeline) {
            'asap' => 'ASAP',
            '1_2_weeks' => '1-2 weeks',
            '1_month' => '1 month',
            '2_3_months' => '2-3 months',
            '3_6_months' => '3-6 months',
            'flexible' => 'Flexible',
            default => $this->timeline
        };
    }

    /**
     * Scope for filtering by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for recent quotes
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
