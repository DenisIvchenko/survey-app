<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModerationLog extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $fillable = ['review_id', 'moderator_id', 'action', 'details'];
    protected $casts = ['details' => 'array', 'created_at' => 'datetime'];
    public function review(): BelongsTo { return $this->belongsTo(Review::class); }
    public function moderator(): BelongsTo { return $this->belongsTo(User::class, 'moderator_id'); }
}