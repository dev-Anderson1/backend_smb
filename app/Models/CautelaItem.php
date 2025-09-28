<?php


namespace App\Models;

use App\Models\Arma;
use App\Models\Algema;
use App\Models\Colete;
use App\Models\Espada;
use App\Models\Cautela;
use App\Models\Material;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CautelaItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cautela_id', 'arma_id', 'colete_id', 'espada_id', 'algema_id', 'outros_materiais', 'quantidade'
    ];

    // Relacionamento com o material (arma, colete, espada, etc.)
    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    // Relacionamento com a arma
    public function arma()
    {
        return $this->belongsTo(Arma::class);
    }

    // Relacionamento com o colete
    public function colete()
    {
        return $this->belongsTo(Colete::class);
    }

    // Relacionamento com a espada
    public function espada()
    {
        return $this->belongsTo(Espada::class);
    }

    // Relacionamento com a algema
    public function algema()
    {
        return $this->belongsTo(Algema::class);
        
    }

    
    public function cautela()
    {
        return $this->belongsTo(Cautela::class);
    }
}
