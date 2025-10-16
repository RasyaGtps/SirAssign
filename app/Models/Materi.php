<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materi';

    protected $fillable = [
        'mapel_id',
        'title',
        'file_path',
        'file_type'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the mapel that owns the materi.
     */
    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    /**
     * Get the user that created the materi (via mapel relationship).
     */
    public function user()
    {
        return $this->hasOneThrough(User::class, Mapel::class, 'id', 'id', 'mapel_id', 'user_id');
    }

    // Accessors for DocumentIndexingService compatibility
    public function getJudulAttribute()
    {
        return $this->attributes['title'] ?? '';
    }

    public function getDeskripsiAttribute()
    {
        return ''; // No description field in current schema
    }

    public function getUserIdAttribute()
    {
        return Auth::id(); // Use current authenticated user
    }
}