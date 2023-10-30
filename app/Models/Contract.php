<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id', 'offer_id', 'project_owner_id', 'offer_owner_id',
        'contract_duration', 'contract_value', 'contract_stages', 'contract_current_stage', 'contract_state', 'penality'
    ];
    function stages()
    {
        return $this->hasMany(Stage::class);
    }
    function project()
    {
        return $this->hasOne(Project::class);
    }
    protected $with = ['stages','project'];
}
