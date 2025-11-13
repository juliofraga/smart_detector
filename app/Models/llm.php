<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Llm extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'provider',
        'model_id',
        'api_base_url',
        'api_key',
        'max_tokens',
        'default_temperature',
        'context_length',
        'pricing_prompt_token',
        'pricing_completion_token',
        'notes',
        'active'
    ];

    public function rules(): array 
    {
        return [
            'name' => 'required|unique:llms,name',
            'provider' => 'required',
            'model_id' => 'required',
            'api_base_url' => 'required',
            'api_key' => 'required'
        ];
    }

    public function feedback(): array 
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'name.unique' => 'Já existe um registro com esse nome. Informe outro nome, por favor.'
        ];
    }
}
