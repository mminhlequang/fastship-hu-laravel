<?php

use Spatie\Menu\Laravel\Menu;
use Spatie\Menu\Laravel\Html;
use Spatie\Menu\Laravel\Link;

Menu::macro('adminlteMenu', function () {
	return Menu::new()
		->addClass('sidebar-menu tree')->setAttribute('data-widget', 'tree');
});

Menu::macro('adminlteSubmenu', function ($submenuName) {
	return Menu::new()->prepend('<a href="#">' . $submenuName . '<span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span></a>')
		->addParentClass('treeview')->addClass('treeview-menu');
});

Menu::macro('sidebar', function ($user, $arLink) {
	$menu = Menu::adminlteMenu();
	//->add( Html::raw( '<div class="btn-warning">MAIN MENU</div>' )->addParentClass( 'text-center header no-padding' ) );
	//->add( Link::to('home', '<i class="fa fa-home"></i><span>Home</span>' ));
	foreach ($arLink as $key => $link) {
		$groupActive = false;
		if (empty($link['permission']) || $user->can($link['permission'])) {
			if (!empty($link['child'])) {
				foreach ($link['child'] as $lchild) {
					if (!isset($lchild['permission']) || (isset($lchild['permission']) && $user->can($lchild['permission']))) {
						$groupActive = true;
						break;
					}
				}
			} else {
				$htmlLink = Html::raw('<a href="' . url($link['href']) . '"><i class="' . $link['icon'] . '"></i><span>' . $link['title'] . '</span></a>');
				if (\request()->is($link['href']) || \request()->is($link['href'] . "/*")) {
					$htmlLink->setActive()->addParentClass('selected');
				}
				if (\request()->is('/') || \request()->is($link['href'])) {
					$htmlLink->setActive()->addParentClass('menu-open');
				}
				$menu->add($htmlLink);
			}
			if ($groupActive) {
				$mn = Menu::adminlteSubmenu('<i class="' . $link['icon'] . '"></i><span>' . $link['title'] . '</span>');
				if (!empty($link['child'])) {
					foreach ($link['child'] as $lchild) {
						if (!isset($lchild['permission']) || $user->can($lchild['permission']) == true) {
							$htmlLink = Html::raw('<a href="' . url($lchild['href']) . '">&nbsp;<i class="' . $lchild['icon'] . '"></i>' . $lchild['title'] . '</a>');
							if (\request()->is($lchild['href']) || \request()->is($lchild['href'] . "/*")) {
								$htmlLink->setActive()->addParentClass('selected');
								$mn->add($htmlLink)->addParentClass('selected');
							} else {
								$mn->add($htmlLink);
							}
						}
					}
				}
				$menu->add($mn);
			}
		}
	}
	return $menu; //->setActiveFromRequest();
});
/*
Menu::macro('sidebar', function () {
	return Menu::adminlteMenu()
		->add(Html::raw('<div class="btn-warning">MAIN MENU</div>')->addParentClass('text-center header no-padding'))
		->action('HomeController@index', '<i class="fa fa-home"></i><span>Home</span>')

		->add(
			Menu::adminlteSubmenu('<i class="fa fa-list"></i><span>Cac module dự án</span>')
			    ->add(Link::to('/languages', 'Ngôn ngữ'))
			    ->add(Link::to('/category', 'Thể loại'))
				->add(Link::to('/foods', 'Món ăn'))
				->add(Link::to('/units', 'Đơn vị tính'))
				->add(Link::to('/companies', 'Công ty'))
				->add(Link::to('/food-files', 'Món ăn - file'))
				->add(Link::to('/company-infos', 'Công ty - giới thiệu'))
		)

		->add(Menu::new()->prepend('<a href="#"><i class="fa fa-users"></i><span>Quản lý người dùng</span> <i class="fa fa-angle-left pull-right"></i></a>')
			->addParentClass('treeview')
			->add(Link::to('/companies', 'Danh sách công ty'))
			->add(Link::to('/admin/users', '<i class="fa fa-user-plus"></i>Tải khoản Người dùng'))->addClass('treeview-menu')
			->add(Link::to('/admin/roles', '<i class="fa fa-user-times"></i> Vai trò người dùng'))
		)
		->add(Menu::new()->prepend('<a href="#"><i class="fa fa-cog"></i><span>Cấu hình</span> <i class="fa fa-angle-left pull-right"></i></a>')
			->addParentClass('treeview')
			->add(Link::to('/admin/backup', '<i class="fa fa-hdd-o"></i>Sao lưu'))->addClass('treeview-menu')
			->add(Link::to('/languages', 'Ngôn ngữ'))
			->add(Link::to('/units', 'Đơn vị tính'))
		)
               ->setActiveFromRequest();
});
*/