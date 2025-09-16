<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classification extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'description',
        'visual_style'
    ];

    public function rules(): array 
    {
        return [
            'description' => 'required',
            'visual_style' => 'required'
        ];
    }

    public function feedback(): array 
    {
        return [
            'required' => 'O campo :attribute é obrigatório'
        ];
    }
}
