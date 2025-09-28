<?php

namespace App\Models;

use App\Models\User;
use App\Models\Municao;
use App\Models\Carregador;
use App\Models\ModeloArma;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Arma extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'municao_id', 'carregador_id', 'modelo_id'];

    // Relação com o usuário (uma arma pertence a um único usuário)
    public function users()
    {
        return $this->belongsTo(User::class); // Um usuário tem muitas armas
    }

    // Relação com a munição (uma arma tem uma munição)
    public function municao()
    {
        return $this->belongsTo(Municao::class, 'municao_id'); // Relacionamento correto com a munição
    }

    // Relação com o carregador (uma arma tem um carregador)
    public function carregador()
    {
        return $this->belongsTo(Carregador::class, 'carregador_id'); // Relacionamento correto com o carregador
    }

    public function modelo()  
{
    return $this->belongsTo(ModeloArma::class, 'modelo_id');
}


}
