<?php

namespace App\Models;

use App\Models\Arma;
use App\Models\Calibre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Municao extends Model
{
    use HasFactory;

    protected $table = 'municoes';
    protected $fillable = ['tipo', 'calibre_id', 'quantidade'];

    public function calibre()
    {
        return $this->belongsTo(Calibre::class);
    }

    public function arma()
{
    return $this->belongsTo(Arma::class);
}

}
