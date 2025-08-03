<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = ['nome', 'descricao', 'quantidade_disponivel'];
}
