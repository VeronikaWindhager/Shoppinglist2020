<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    protected $fillable = ['shoppinglist_id','name','amount','max_price'];

    public function shoppinglists():BelongsTo{
        return $this->belongsTo(Shoppinglist::class);
    }
}
