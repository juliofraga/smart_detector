<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginError extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'error_count',
        'blocked',
        'blocked_at',
    ];

    protected $casts = [
        'blocked' => 'boolean',
        'blocked_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function incrementErrorCount(): void
    {
        $this->increment('error_count');
    }

    public function block(): void
    {
        $this->update([
            'blocked' => 1,
            'blocked_at' => now(),
        ]);
    }

    public function resetErrors(): void
    {
        $this->update([
            'error_count' => 0,
            'blocked' => false,
            'blocked_at' => null,
        ]);
    }

    public function isBlocked(): bool
    {
        return $this->blocked;
    }
}
