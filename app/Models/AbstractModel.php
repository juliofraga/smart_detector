<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractModel extends Model
{
    
    use HasFactory, Notifiable;

    protected $fillable = [];
    
    abstract public function rules(): array;

    abstract public function feedback(): array;
    
}
