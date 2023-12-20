<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Candidature extends Model
{
    use HasFactory;
    public function user() :BelongsToMany 
    {
        return $this -> BelongsToMany (   User::class);
    }

    public function Formation() :BelongsToMany 
    {
        return $this -> BelongsToMany (   Formation::class);
    }

    protected $fillable = [
    
        'user_id',
        'formation_id',
    ];
}