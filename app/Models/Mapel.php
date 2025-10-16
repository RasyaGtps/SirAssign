<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    protected $fillable = ['nama_mapel', 'deskripsi'];

    public function materi()
    {
        return $this->hasMany(Materi::class);
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_mapel');
    }

    public function gurus()
    {
        return $this->users()->where('role', 'guru');
    }
}
