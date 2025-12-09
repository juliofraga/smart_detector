<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends BaseModel
{
    use HasFactory;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $guarded = [];

    protected $casts = [
        'event_date_time' => 'datetime:Y-m-d H:i:s',
    ];

    public function rules(): array 
    {
        return [
            'description' => 'required',
            'types_id' => 'required',
            'classifications_id' => 'required',
            'analysys_id' => 'required',
            'event_date_time' => 'required',
            'ids_id' => 'required'
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

    public function type()
    {
        return $this->belongsTo(Type::class, 'types_id');
    }

    public function idsAgent()
    {
        return $this->belongsTo(ids_agent::class, 'ids_id');
    }
}
