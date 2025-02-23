<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	use BaseModel;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'label'];

    /**
     * A role may be given various permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Grant the given permission to a role.
     *
     * @param  Permission $permission
     *
     * @return mixed
     */
    public function givePermissionTo(Permission $permission)
    {
        return $this->permissions()->save($permission);
    }

	/**
	 * Label color
	 */
	public function getColorAttribute(){
		$arColor = ["success", "warning", "danger", "info"];
		if($this->name == "admin"){
			return $arColor[0];
		}else if($this->name == "sale"){
			return $arColor[3];
		}
		return $arColor[1];
	}
}
