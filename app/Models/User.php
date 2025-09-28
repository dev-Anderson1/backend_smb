<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Opm;
use App\Models\Arma;
use App\Models\Algema;
use App\Models\Colete;
use App\Models\Espada;
use App\Models\Cautela;
use App\Models\Carregador;
use App\Models\ModeloArma;
use App\Models\Posto_Graduacao;
use Laravel\Passport\HasApiTokens; 
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'opm_id',
        'posto_graduacoes_id',
        'colete_id',
        'espada_id',
        'algema_id',
        'outros_materiais',
        'arma_id',
        'apelido',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'opm_id' => 'integer',
        'posto_graduacoes_id' => 'integer',
        'colete_id' => 'integer',
        'espada_id' => 'integer',
        'algema_id' => 'integer',
        'outros_materiais' => 'string',
        'arma_id' => 'integer',
        ' apelido' => 'string',
    ];

    public function opm()
{
    return $this->belongsTo(Opm::class);
}
public function postoGraduacao()
{
    return $this->belongsTo(Posto_Graduacao::class, 'posto_graduacoes_id' );
}

public function colete()
{
    return $this->belongsTo(Colete::class);
}

public function algema()
{
    return $this->belongsTo(Algema::class);
}

public function espada()
{
    return $this->belongsTo(Espada::class);
}
public function carregador()
{
    return $this->belongsTo(Carregador::class);
}

public function arma()
{
    return $this->belongsTo(Arma::class);
}

public function modelo()
{
    return $this->belongsTo(ModeloArma::class, 'modelo_id');
}

public function cautelas()
{
    return $this->hasMany(Cautela::class);
}




}
