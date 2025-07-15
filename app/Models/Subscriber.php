<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Subscriber extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'subscribed',
        'subscribed_at',
        'unsubscribed_at',
        'source',
        'ip_address',
        'user_agent',
        'token',
        'verification_token',
        'verified_at',
        'preferences',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'subscribed' => 'boolean',
            'subscribed_at' => 'datetime',
            'unsubscribed_at' => 'datetime',
            'verified_at' => 'datetime',
            'preferences' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subscriber) {
            // Generate verification token if not provided
            if (empty($subscriber->verification_token) && !$subscriber->subscribed) {
                $subscriber->verification_token = Str::random(32);
            }

            // Set subscribed_at if subscribing
            if ($subscriber->subscribed && !$subscriber->subscribed_at) {
                $subscriber->subscribed_at = now();
            }

            // Set status based on subscription
            if (empty($subscriber->status)) {
                $subscriber->status = $subscriber->subscribed ? 'active' : 'pending';
            }
        });

        static::updating(function ($subscriber) {
            // Handle subscription status changes
            if ($subscriber->isDirty('subscribed')) {
                if ($subscriber->subscribed && !$subscriber->getOriginal('subscribed')) {
                    // Just subscribed
                    $subscriber->subscribed_at = now();
                    $subscriber->unsubscribed_at = null;
                    $subscriber->verification_token = null;
                    $subscriber->verified_at = now();
                    $subscriber->status = 'active';
                } elseif (!$subscriber->subscribed && $subscriber->getOriginal('subscribed')) {
                    // Just unsubscribed
                    $subscriber->unsubscribed_at = now();
                    $subscriber->status = 'unsubscribed';
                }
            }
        });
    }

    /**
     * Get the subscriber's full name.
     */
    public function getFullNameAttribute(): ?string
    {
        if ($this->first_name || $this->last_name) {
            return trim($this->first_name . ' ' . $this->last_name);
        }
        return null;
    }

    /**
     * Get the subscriber's display name (full name or email).
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->full_name ?: $this->email;
    }

    /**
     * Get the subscriber's initials.
     */
    public function getInitialsAttribute(): string
    {
        if ($this->first_name || $this->last_name) {
            return strtoupper(
                substr($this->first_name ?: '', 0, 1) .
                substr($this->last_name ?: '', 0, 1)
            );
        }
        return strtoupper(substr($this->email, 0, 2));
    }

    /**
     * Check if the subscriber is verified.
     */
    public function getIsVerifiedAttribute(): bool
    {
        return !is_null($this->verified_at);
    }

    /**
     * Check if the subscriber is pending verification.
     */
    public function getIsPendingAttribute(): bool
    {
        return !$this->subscribed && !is_null($this->verification_token);
    }

    /**
     * Get the subscription duration in days.
     */
    public function getSubscriptionDurationAttribute(): ?int
    {
        if (!$this->subscribed_at) {
            return null;
        }

        $endDate = $this->unsubscribed_at ?: now();
        return $this->subscribed_at->diffInDays($endDate);
    }

    /**
     * Get formatted subscription date.
     */
    public function getFormattedSubscribedAtAttribute(): ?string
    {
        return $this->subscribed_at?->format('M j, Y');
    }

    /**
     * Get the subscriber's preferred frequency.
     */
    public function getPreferredFrequencyAttribute(): string
    {
        return $this->preferences['frequency'] ?? 'weekly';
    }

    /**
     * Get the subscriber's preferred topics.
     */
    public function getPreferredTopicsAttribute(): array
    {
        return $this->preferences['topics'] ?? [];
    }

    /**
     * Get the subscriber's preferred format.
     */
    public function getPreferredFormatAttribute(): string
    {
        return $this->preferences['format'] ?? 'html';
    }

    /**
     * Check if subscriber prefers a specific topic.
     */
    public function prefersTopic(string $topic): bool
    {
        return in_array($topic, $this->preferred_topics);
    }

    /**
     * Scope to get only subscribed subscribers.
     */
    public function scopeSubscribed($query)
    {
        return $query->where('subscribed', true)->where('status', 'active');
    }

    /**
     * Scope to get only unsubscribed subscribers.
     */
    public function scopeUnsubscribed($query)
    {
        return $query->where('subscribed', false)->where('status', 'unsubscribed');
    }

    /**
     * Scope to get only pending verification subscribers.
     */
    public function scopePending($query)
    {
        return $query->where('subscribed', false)
                    ->where('status', 'pending')
                    ->whereNotNull('verification_token');
    }

    /**
     * Scope to get verified subscribers.
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    /**
     * Scope to get recent subscribers.
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope to search subscribers.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('email', 'like', "%{$search}%")
              ->orWhere('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%");
        });
    }

    /**
     * Scope to filter by source.
     */
    public function scopeFromSource($query, $source)
    {
        return $query->where('source', $source);
    }

    /**
     * Scope to filter by status.
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by preferred frequency.
     */
    public function scopeWithFrequency($query, $frequency)
    {
        return $query->whereJsonContains('preferences->frequency', $frequency);
    }

    /**
     * Scope to filter by preferred topic.
     */
    public function scopeWithTopic($query, $topic)
    {
        return $query->whereJsonContains('preferences->topics', $topic);
    }

    /**
     * Subscribe the subscriber.
     */
    public function subscribe(): bool
    {
        return $this->update([
            'subscribed' => true,
            'subscribed_at' => now(),
            'unsubscribed_at' => null,
            'verification_token' => null,
            'verified_at' => now(),
            'status' => 'active',
        ]);
    }

    /**
     * Unsubscribe the subscriber.
     */
    public function unsubscribe(): bool
    {
        return $this->update([
            'subscribed' => false,
            'unsubscribed_at' => now(),
            'status' => 'unsubscribed',
        ]);
    }

    /**
     * Verify the subscriber.
     */
    public function verify(): bool
    {
        return $this->update([
            'subscribed' => true,
            'subscribed_at' => now(),
            'verification_token' => null,
            'verified_at' => now(),
            'status' => 'active',
        ]);
    }

    /**
     * Mark as bounced.
     */
    public function markAsBounced(): bool
    {
        return $this->update([
            'status' => 'bounced',
            'subscribed' => false,
        ]);
    }

    /**
     * Mark as complained.
     */
    public function markAsComplained(): bool
    {
        return $this->update([
            'status' => 'complained',
            'subscribed' => false,
        ]);
    }

    /**
     * Update preferences.
     */
    public function updatePreferences(array $preferences): bool
    {
        $currentPreferences = $this->preferences ?: [];
        $newPreferences = array_merge($currentPreferences, $preferences);

        return $this->update(['preferences' => $newPreferences]);
    }
}
