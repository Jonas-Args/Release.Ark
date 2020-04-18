<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TopupTransaction extends Model
{
    protected $table = 'topup_transactions';

	public function user()
    {
        return $this->belongsTo(User::class);
    }
}
