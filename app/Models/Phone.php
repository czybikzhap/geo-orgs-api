<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'number',
    ];

    /**
     * Телефон принадлежит организации
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
