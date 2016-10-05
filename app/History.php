<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class History extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'ip', 'browser','user_id'
    ];

    protected $guarded = array('id');

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
