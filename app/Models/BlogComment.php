<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class BlogComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'blog_id',
        'user_id',
        'parent_id',
        'guest_name',
        'guest_email',
        'content',
        'is_approved',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the blog that owns the comment.
     */
    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }

    /**
     * Get the user that owns the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(BlogComment::class, 'parent_id');
    }

    /**
     * Get the child comments (replies).
     */
    public function children(): HasMany
    {
        return $this->hasMany(BlogComment::class, 'parent_id')
                    ->where('is_approved', true)
                    ->orderBy('created_at', 'asc');
    }

    /**
     * Get all replies including nested ones.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(BlogComment::class, 'parent_id')
                    ->with('replies')
                    ->where('is_approved', true)
                    ->orderBy('created_at', 'asc');
    }

    /**
     * Scope to get only approved comments.
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope to get only root comments (no parent).
     */
    public function scopeRootComments($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope to get comments for a specific blog.
     */
    public function scopeForBlog($query, $blogId)
    {
        return $query->where('blog_id', $blogId);
    }

    /**
     * Get the comment author name.
     */
    public function getAuthorNameAttribute(): string
    {
        return $this->user?->username ?? $this->guest_name ?? 'Anonymous';
    }

    /**
     * Get the comment author email.
     */
    public function getAuthorEmailAttribute(): string
    {
        return $this->user?->email ?? $this->guest_email ?? '';
    }

    /**
     * Get the comment author avatar.
     */
    public function getAuthorAvatarAttribute(): ?string
    {
        if ($this->user?->avatar) {
            return asset('storage/' . $this->user->avatar);
        }
        
        // Generate Gravatar for guest users
        if ($this->guest_email) {
            $hash = md5(strtolower(trim($this->guest_email)));
            return "https://www.gravatar.com/avatar/{$hash}?d=mp&s=150";
        }
        
        return asset('assets/client/images/user/1.jpg');
    }

    /**
     * Check if comment is by a guest user.
     */
    public function getIsGuestAttribute(): bool
    {
        return is_null($this->user_id);
    }

    /**
     * Check if comment can be deleted by given user.
     */
    public function canBeDeletedBy($user): bool
    {
        if (!$user) {
            return false;
        }

        // Admin can delete any comment
        if ($user->is_admin ?? false) {
            return true;
        }

        // User can delete their own comment
        return $this->user_id === $user->id;
    }

    /**
     * Check if comment can be edited by given user.
     */
    public function canBeEditedBy($user): bool
    {
        if (!$user) {
            return false;
        }

        // Only the comment author can edit (within time limit)
        if ($this->user_id !== $user->id) {
            return false;
        }

        // Allow editing within 15 minutes of creation
        return $this->created_at->diffInMinutes(now()) <= 15;
    }

    /**
     * Get comment depth level.
     */
    public function getDepthLevel(): int
    {
        $depth = 0;
        $parent = $this->parent;
        
        while ($parent) {
            $depth++;
            $parent = $parent->parent;
        }
        
        return $depth;
    }

    /**
     * Get short content for notifications.
     */
    public function getShortContentAttribute(): string
    {
        return Str::limit($this->content, 100);
    }

    /**
     * Boot method to add model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-capture IP and User Agent when creating
        self::creating(function ($comment) {
            $comment->ip_address = request()->ip();
            $comment->user_agent = request()->userAgent();
        });

        // Clean up child comments when parent is deleted
        self::deleting(function ($comment) {
            // Soft delete all child comments
            $comment->children()->delete();
        });
    }
}