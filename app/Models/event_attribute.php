<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class event_attribute extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'position',
        'field_name',
        'display_value',
        'type_field',
        'show',
        'enabled'
    ];

    public function rules(): array 
    {
        return [
            'field_name' => 'required|unique:event_attributes,field_name',
            'display_value' => 'required'
        ];
    }

    public function feedback(): array 
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'field_name.unique' => 'Já existe um campo com esse nome. Informe outro nome, por favor.'
        ];
    }
}
