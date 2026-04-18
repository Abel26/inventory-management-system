<?php

namespace App\Models;

use App\Enums\WorkPriority;
use App\Enums\WorkStatus;
use App\Enums\WorkType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WorkLog extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'work_code',
        'user_id',
        'description',
        'work_date',
        'start_time',
        'end_time',
        'break_duration',
        'total_work_minutes',
        'status',
        'priority',
        'work_type',
        'completion_percentage',
        'notes',
        'location_id',
        'admin_comments',
        'admin_commented_at',
        'admin_commented_by',
        'attachment_path',
        'attachment_name',
        'attachment_size',
        'attachment_mime_type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => WorkStatus::class,
        'priority' => WorkPriority::class,
        'work_type' => WorkType::class,
        'work_date' => 'date',
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
        'completion_percentage' => 'integer',
        'break_duration' => 'integer',
        'total_work_minutes' => 'integer',
        'admin_commented_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'work_duration', 
        'status_badge', 
        'priority_badge', 
        'show_url', 
        'edit_url', 
        'can_edit', 
        'can_delete'
    ];

    /**
     * Get the show URL attribute.
     */
    public function getShowUrlAttribute(): string
    {
        return route('work-logs.show', $this->id);
    }

    /**
     * Get the edit URL attribute.
     */
    public function getEditUrlAttribute(): string
    {
        return route('work-logs.edit', $this->id);
    }

    /**
     * Get the can_edit attribute for JSON.
     */
    public function getCanEditAttribute(): bool
    {
        return $this->canEdit();
    }

    /**
     * Get the can_delete attribute for JSON.
     */
    public function getCanDeleteAttribute(): bool
    {
        $user = Auth::user();
        if ($user && ($user->isSuperAdmin() || $user->isAdmin())) {
            return true;
        }
        return false;
    }

    /**
     * Get the user that owns the work log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the location (gedung) for the work log.
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Gedung::class, 'location_id');
    }

    /**
     * Get the admin who commented on the work log.
     */
    public function adminCommenter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_commented_by');
    }

    /**
     * Scope a query to only include work logs for a specific user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include work logs for a specific date.
     */
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('work_date', $date);
    }

    /**
     * Scope a query to only include work logs within a date range.
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('work_date', [$startDate, $endDate]);
    }

    /**
     * Scope a query to only include work logs with a specific status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include work logs with a specific work type.
     */
    public function scopeByWorkType($query, $type)
    {
        return $query->where('work_type', $type);
    }

    /**
     * Scope a query to only include work logs with a specific priority.
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope a query to only include today's work logs.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('work_date', Carbon::today());
    }

    /**
     * Scope a query to only include this week's work logs.
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('work_date', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ]);
    }

    /**
     * Scope a query to only include this month's work logs.
     */
    public function scopeThisMonth($query)
    {
        return $query->whereBetween('work_date', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ]);
    }

    /**
     * Scope a query to only include active (not completed) work logs.
     */
    public function scopeActive($query)
    {
        return $query->where('status', '!=', WorkStatus::COMPLETED->value);
    }

    /**
     * Scope a query to only include completed work logs.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', WorkStatus::COMPLETED->value);
    }

    /**
     * Scope a query to only include locked work logs (older than 7 days).
     */
    public function scopeLocked($query)
    {
        return $query->where('created_at', '<', Carbon::now()->subDays(7));
    }

    /**
     * Scope a query to only include unlocked work logs (not older than 7 days).
     */
    public function scopeUnlocked($query)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays(7));
    }

    /**
     * Get the total work hours attribute.
     */
    public function getTotalWorkHoursAttribute(): float
    {
        return $this->total_work_minutes ? round($this->total_work_minutes / 60, 2) : 0;
    }

    /**
     * Get the work duration formatted attribute.
     */
    public function getWorkDurationAttribute(): string
    {
        if (!$this->total_work_minutes) {
            return '-';
        }

        $hours = floor($this->total_work_minutes / 60);
        $minutes = $this->total_work_minutes % 60;

        $hoursLabel = __('work_logs.units.hours_short');
        $minutesLabel = __('work_logs.units.minutes_short');

        if ($hours > 0 && $minutes > 0) {
            return $hours . $hoursLabel . ' ' . $minutes . $minutesLabel;
        } elseif ($hours > 0) {
            return $hours . $hoursLabel;
        } else {
            return $minutes . $minutesLabel;
        }
    }

    /**
     * Get the status badge HTML attribute.
     */
    public function getStatusBadgeAttribute(): string
    {
        $status = $this->status;
        return sprintf(
            '<span class="px-2 py-1 text-xs font-medium rounded-full %s">%s</span>',
            $status->getColor(),
            $status->getLabel()
        );
    }

    /**
     * Get the priority badge HTML attribute.
     */
    public function getPriorityBadgeAttribute(): string
    {
        $priority = $this->priority;
        return sprintf(
            '<span class="px-2 py-1 text-xs font-medium rounded-full %s">%s</span>',
            $priority->getColor(),
            $priority->getLabel()
        );
    }

    /**
     * Calculate total work minutes based on start time, end time, and break duration.
     */
    public function calculateTotalWorkMinutes(): ?int
    {
        if (!$this->start_time || !$this->end_time) {
            return null;
        }

        $startTime = Carbon::parse($this->start_time);
        $endTime = Carbon::parse($this->end_time);
        $totalMinutes = $endTime->diffInMinutes($startTime, true); // Use absolute difference

        return max(0, $totalMinutes - $this->break_duration);
    }

    /**
     * Check if the work log is locked (older than 7 days).
     */
    public function isLocked(): bool
    {
        return $this->created_at->lt(Carbon::now()->subDays(7));
    }

    /**
     * Check if the work log can be edited.
     */
    public function canEdit(?User $user = null): bool
    {
        $user = $user ?? Auth::user();

        // Admin can always edit
        if ($user && ($user->isSuperAdmin() || $user->isAdmin())) {
            return true;
        }

        // Work log must not be locked
        if ($this->isLocked()) {
            return false;
        }

        // User must be the owner
        return $user && $this->user_id === $user->id;
    }

    /**
     * Add admin comment to work log.
     */
    public function addAdminComment(string $comment, int $adminId): void
    {
        $this->admin_comments = $comment;
        $this->admin_commented_at = now();
        $this->admin_commented_by = $adminId;
        $this->save();
    }

    /**
     * Check if work log has an attachment.
     */
    public function hasAttachment(): bool
    {
        return !empty($this->attachment_path);
    }

    /**
     * Get the full URL for the attachment.
     */
    public function getAttachmentUrlAttribute(): ?string
    {
        if (!$this->attachment_path) {
            return null;
        }

        return asset('storage/' . $this->attachment_path);
    }

    /**
     * Delete the attachment file and clear attachment fields.
     */
    public function deleteAttachment(): void
    {
        if ($this->attachment_path && file_exists(storage_path('app/public/' . $this->attachment_path))) {
            unlink(storage_path('app/public/' . $this->attachment_path));
        }

        $this->attachment_path = null;
        $this->attachment_name = null;
        $this->attachment_size = null;
        $this->attachment_mime_type = null;
        $this->save();
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($workLog) {
            if (empty($workLog->work_code)) {
                $workLog->work_code = self::generateWorkCode();
            }
            if (empty($workLog->total_work_minutes) && $workLog->start_time && $workLog->end_time) {
                $workLog->total_work_minutes = $workLog->calculateTotalWorkMinutes();
            }
        });

        static::updating(function ($workLog) {
            if ($workLog->isDirty('start_time') || $workLog->isDirty('end_time') || $workLog->isDirty('break_duration')) {
                $workLog->total_work_minutes = $workLog->calculateTotalWorkMinutes();
            }
        });
    }

    /**
     * Generate a unique work code.
     */
    public static function generateWorkCode(): string
    {
        $date = now()->format('Ymd');
        $prefix = "WP-{$date}";

        $lastWorkLog = self::withTrashed()
            ->where('work_code', 'like', "{$prefix}%")
            ->orderBy('work_code', 'desc')
            ->first();

        if ($lastWorkLog instanceof self) {
            $lastNumber = (int) substr($lastWorkLog->work_code, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "{$prefix}-{$newNumber}";
    }
}
