<?php

namespace App\Models;

use App\Models\User;
use App\Models\Calibre;
use App\Models\Arma;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModeloArma extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'calibre_id', 'numero_serie'];

    public function calibre()
    {
        return $this->belongsTo(Calibre::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'modelo_id');
    }

    public function armas()
{
    return $this->hasMany(Arma::class);
}

  

}
