<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = ['user_id','shoppinglist_id','content'];

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function shoppinglists():BelongsTo{
        return $this->belongsTo(Shoppinglist::class);
    }
}
