<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Posto_Graduacao extends Model
{
    use HasFactory;

    protected $fillable = ['nome'];
    protected $table = 'posto_graduacoes';

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
