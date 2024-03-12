<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slots extends Model
{

    protected $table = 'slots_transactions';

    protected $fillable = [
        'id',
        'game',
        'game_id',
        'user',
        'action',
        'action_id',
        'charge',
        'status',
        'created_date'
    ];

    static function lastId() {
        $slots = self::select('id')->latest('id')->first();
        return $slots['id'];
    }

    static function checkReplicated($action_id) {
        $action = self::select('id','game','game_id','user','action','action_id','charge','status')->where('action_id', $action_id)->first();
        return $action;
    }
}
