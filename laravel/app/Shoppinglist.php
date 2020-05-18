<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shoppinglist extends Model
{
    protected $fillable = [ 'title' , 'due_date' , 'total_price' , 'image'];

    public function user():BelongsToMany{
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function items():HasMany{
        return $this->hasMany(Item::class);
    }

    public function comments():HasMany{
        return $this->hasMany(Comment::class);
    }
}
