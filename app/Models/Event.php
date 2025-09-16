<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends AbstractModel
{
    use HasFactory;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'description',
        'ip_address',
        'type',
        'threat_classification',
        'ai_analysys',
        'geographical_origin',
        'request',
        'event_date_time'
    ];

    protected $casts = [
        'event_date_time' => 'datetime',
    ];

    public function rules(): array 
    {
        return [
            'description' => 'required',
            'type' => 'required',
            'threat_classification' => 'required',
            'ai_analysys' => 'required',
            'event_date_time' => 'required'
        ];
    }

    public function feedback(): array 
    {
        return [
            'required' => 'O campo :attribute é obrigatório'
        ];
    }
}
