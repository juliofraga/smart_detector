<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ids_agent extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'hostname',
        'ip_local'
    ];

    public function rules(): array 
    {
        return [
            'name' => 'required'
        ];
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'ids_id');
    }
}
