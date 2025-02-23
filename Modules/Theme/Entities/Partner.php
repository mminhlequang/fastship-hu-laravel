<?php

namespace Modules\Theme\Entities;

use App\Traits\ImageResize;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
	use ImageResize;
	public static $imageFolder = "partner";
	public static $imageMaxWidth = 300;
	public static $imageMaxHeight = 200;

    protected $table = 'partner';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'link', 'image', 'active', 'arrange'];

}
