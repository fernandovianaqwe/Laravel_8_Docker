<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cantores extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'estilos',
    ];

    public function Albuns()
  {
    return $this->hasMany(Albuns::class);
  }
}
