<?php

namespace App\Models;

use App\Models\Municao;
use App\Models\ModeloArma;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Calibre extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'medidas'];

    public function modelosArmas()
    {
        return $this->hasMany(ModeloArma::class, 'calibre_id');
    }
    public function municoes()
{
    return $this->hasMany(Municao::class);
}

}

