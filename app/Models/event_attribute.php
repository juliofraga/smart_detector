<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class event_attribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'position',
        'field_name',
        'display_value',
        'show',
        'enabled'
    ];

    public function rules(): array 
    {
        return [
            'field_name' => 'required',
            'display_value' => 'required'
        ];
    }

    public function feedback(): array 
    {
        return [
            'required' => 'O campo :attribute é obrigatório'
        ];
    }
}
