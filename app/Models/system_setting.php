<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

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

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('system_settings');
        });

        static::deleted(function () {
            Cache::forget('system_settings');
        });
    }
}
