<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'content_type', 'content_id', 'content', 'url', 'method', 'action', 'ip', 'agent', 'user_id'
    ];
    public function user(){
        return $this->belongsTo('App\User');
    }
	public static function boot(){
		parent::boot();

		static::addGlobalScope('soft', function (Builder $builder) {
			$builder->orderBy('created_at', 'desc');
		});
	}
}
