<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\IssueType;
use App\Enums\Priority;
use App\Enums\Status;

class Report extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'report_code',
        'user_id',
        'reportable_id',
        'reportable_type',
        'issue_type',
        'priority',
        'description',
        'photo_path',
        'status',
        'resolved_at',
        'resolved_by',
    ];
    
    protected $casts = [
        'issue_type' => IssueType::class,
        'priority' => Priority::class,
        'status' => Status::class,
    ];
    
    /**
     * Get the asset that this report is about.
     */
    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }
    
    /**
     * Get the user who created this report.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the user who resolved this report.
     */
    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
