<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    use HasFactory;
    protected $fillable = [
        'id', 'contract_id', 'stage_title', 'stage_start_date', 'stage_due_date', 'state', 'note', 'submit_to_review', 'submit_to_review_date', 'review_period', 'review_result', 'review_result_date', 'is_accepted'
    ];
    function contract(){
        return $this->belongsTo(Contract::class);
    }
    //protected $with = ['contract'];
}
