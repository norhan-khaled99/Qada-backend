<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','project_id', 'offer_duration', 'offer_details', 'offer_value', 'offer_stages','state'];
    function user(){
        return $this->belongsTo(User::class);
    }
    function project(){
        return $this->belongsTo(Project::class);
    }
    protected $with = ['user','project'];
}
