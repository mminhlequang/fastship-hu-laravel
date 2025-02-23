<?php
namespace App\Models;

trait BaseModel {
	/**
	 * Text gender: 1 - Name, 0 - Nu
	 * @return string
	 */
	public function getTextGenderAttribute(){
		return $this->gender===1?__('message.user.gender_male'):($this->gender===0?__('message.user.gender_female'):"");
	}
	/**
	 * Get price booking - by agent or not agent
	 * Lấy giá bán - giá theo đại lý hoặc ko
	 * @return mixed
	 */
	public function getPriceBooking(){
		return \Auth::user()->agent && $this->priceAgent ? optional($this->priceAgent)->price : $this->price;
	}

	/**
	 * Where by role
	 * @param $query
	 * @param $type: ap dung cho booking: tours|journeys
	 *
	 * @return mixed
	 */
	public function scopeByRole($query, $type = null)
	{
		if(\Auth::check()) {
			$modelName = class_basename( get_class( $this ) );
			switch ( $modelName ) {
				case 'User':
					if ( \Auth::user()->roleBelongToAgent() ) {
						return $query->where( 'agent_id', '=', \Auth::user()->agent_id );
					}
					break;
				case 'Booking':
					if(\Auth::check()) {
						//book tours|journeys
						$query = $query->whereHas($type, function ( $query ) {
							$query->withTrashed();
						});
						//dai ly
						if ( \Auth::user()->roleBelongToAgent() ) {
							if ( \Auth::user()->isEmployeeAgent() ) {//nhan vien dai ly: chi hien thi booking cua chinh minh tao
								return $query->where( 'creator_id', \Auth::user()->id );
							}else {//admin dai ly: hien thi tat ca booking cua nhan vien dai ly này tạo
								return $query->whereHas( 'creator', function ( $query ) {
									$query->withTrashed()->where( 'agent_id', '=', \Auth::user()->agent_id );
								} );
							}
						} elseif ( \Auth::user()->roleBelongToCompany() ) {//congty
							if ( \Auth::user()->isEmployeeCompany() ) {//Nhân viên công ty: hiển thị booking của chính họ tạo và booking họ quản lý
								return $query->where( function ( $query ) use ($type){
									$query->where( 'creator_id', \Auth::user()->id )
									      ->orWhereHas( $type, function ( $query ) {
										      $query->withTrashed()->whereHas( 'managers', function ( $query ) {
											      $query->where( 'user_id', \Auth::user()->id );
										      } );
									      } );
								} );
							} else {//admin công ty | nhan vien quan ly booking: hiển thị tất cả booking của công ty(cả công ty và đại lý)
								return $query->whereHas( 'creator', function ( $query ) {
									$query->withTrashed()->where( 'company_id', '=', \Auth::user()->company_id );
								} );
							}
						}
					}
					break;
				case 'Role':
					if (\Auth::user()->roleBelongToAgent()){
						return $query->whereIn( 'name', [config('settings.roles.agent_admin'), config('settings.roles.agent_employee')] );
					}
					break;
			}
		}
	}
	/** addGlobalScope Modal **/
	/*public static function bootGlobalScopeAgent($builder){
		if(\Auth::check()) {
			if ( ! \Auth::user()->isAdminCompany() ) {
				$builder->where( 'company_id', '=', \Auth::user()->company_id );
			}
		}
	}*/
	/**
	 * Boot creating model
	 *
	 * Prepare data by role when create
	 *
	 * @param $model
	 */
	public static function bootCreatingByRole($model){
		if(!\Auth::check()) return;
		$modelName = class_basename( get_class( $model ) );
		switch ( $modelName ) {
			case 'User':
				if(!\Auth::user()->isAdminCompany()){
					if(\Auth::user()->roleBelongToAgent()){
						$model->agent_id = \Auth::user()->agent_id;
					}
				}
				break;
			case 'Agent':
				/*if(!\Auth::user()->isAdminCompany()){
					$model->company_id = \Auth::user()->company_id;
				}*/
				break;
			case 'Role':

				break;
		}
	}
}