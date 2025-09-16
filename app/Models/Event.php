<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends BaseModel
{
    use HasFactory;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'description',
        'ip_address',
        'type',
        'classifications_id',
        'analysys_id',
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
            'classifications_id' => 'required',
            'analysys_id' => 'required',
            'event_date_time' => 'required'
        ];
    }

    public function feedback(): array 
    {
        return [
            'required' => 'O campo :attribute é obrigatório'
        ];
    }

    public function classification()
    {
        return $this->belongsTo(Classification::class, 'classifications_id');
    }

    public function analysys()
    {
        return $this->belongsTo(Analysys::class, 'analysys_id');
    }
}
