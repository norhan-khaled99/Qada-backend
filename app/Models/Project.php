<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'project_title', 'project_details', 'space', 'service_category', 'area', 'city',
        'offer_choosing_date', 'project_days_limit', 'last_offers_date', 'request_qty_tables',
        'request_engs', 'state', 'note', 'title_deed', 'owner_id', 'other_files', 'delivery_date', 'offer_id'
    ];
    public function offers()
    {
        return $this->hasMany(Offer::class)->withOut('project');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    protected $with = ['offers','comments'];

    protected $casts = [
        'other_files' => 'array'
    ];
}
