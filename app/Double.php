<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Double extends Model {
	
	const STATUS_NOT_STARTED = 0;
    const STATUS_PLAYING = 1;
    const STATUS_PRE_FINISH = 2;
    const STATUS_FINISHED = 3;
	
    protected $table = 'double';
    
    protected $fillable = ['winner_color', 'pot', 'winner_number', 'status', 'ranked', 'hash', 'profit'];
    
    protected $hidden = ['created_at', 'updated_at'];
}
