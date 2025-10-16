<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $table = 'tugas';

    protected $fillable = [
        'mapel_id',
        'judul',
        'pertanyaan',
        'tingkat_kesulitan',
        'similarity_score',
        'matched_materials'
    ];

    protected $casts = [
        'matched_materials' => 'array',
        'similarity_score' => 'decimal:4'
    ];

    /**
     * Get the mapel that owns the tugas.
     */
    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }
}