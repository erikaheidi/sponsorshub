<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credential extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['access_token', 'service_name'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
