<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Type extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'description'
    ];

    public function rules(): array 
    {
        return [
            'description' => 'required'
        ];
    }
}
