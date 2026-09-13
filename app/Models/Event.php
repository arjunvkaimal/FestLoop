<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'coordinator_id',
        'title',
        'description',
        'category',
        'venue',
        'start_time',
        'end_time',
        'registration_deadline',
        'capacity_limit',
        'banner_image',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'registration_deadline' => 'datetime',
        ];
    }

    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function registeredUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'registrations')
            ->withPivot('status', 'registered_at')
            ->withTimestamps();
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('status', 'published');
    }

    public function scopeUpcoming(Builder $query): void
    {
        $query->where('start_time', '>', now());
    }

    public function scopeByCategory(Builder $query, string $category): void
    {
        $query->where('category', $category);
    }

    public function getIsFullAttribute(): bool
    {
        return $this->registrations()->count() >= $this->capacity_limit;
    }

    public function getRegistrationOpenAttribute(): bool
    {
        return $this->registration_deadline > now() 
            && $this->status === 'published' 
            && !$this->is_full;
    }
}
