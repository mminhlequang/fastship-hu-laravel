<?php

namespace App\Models;

class Menu
{
    public function getMenu($linkMod){
        $modules = \Module::all();
        $mod = [];
        foreach ($modules as $index=>$item){
            if ($item->active == 1)
                if ($item->name != 'Booking')
                    if (!empty(config($item->alias . '.' . $linkMod)))
                    $mod[] = config($item->alias . '.' . $linkMod);
        }
        return $mod;
    }
}
