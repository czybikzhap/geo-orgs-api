<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'building_id',
    ];

    /**
     * Организация находится в одном здании
     */
    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * У организации может быть несколько телефонов
     */
    public function phones()
    {
        return $this->hasMany(Phone::class);
    }

    /**
     * Организация может заниматься несколькими видами деятельности
     */
    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_organization');
    }
}
