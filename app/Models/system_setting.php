<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class system_setting extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'value'
    ];

    public function rules(): array 
    {
        return [
            'value' => 'required'
        ];
    }

    public function feedback(): array 
    {
        return [
            'required' => 'O campo :attribute é obrigatório'
        ];
    }
}
