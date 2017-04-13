<?php
namespace App\Helpers;


class Helper
{
    /*private $sub_menus = '';*/
	public function __construct() {
		
	}
    public static function getSubmenus($menu_lists, $parent_menus)
    {
        $sub_menus = '';
		$sub_menus .= 
            '<li class="treeview">
                <a href="javascript:;"><i class="fa fa-' . $menu_lists['menu_icon'] . '"></i> <span>'. $menu_lists['menu_name'] .' </span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">';

                    $filter_menus = 
                        array_filter($parent_menus, function($value) use($menu_lists) {
                            return $value['parent_id'] == $menu_lists['id'];
                        });

                    foreach ($filter_menus as $menus) {
                        if(!$menus['is_child']) {
                            $sub_menus .= '<li><a href="' . \URL::to($menus['menu_link']) . '"><i class="fa fa-' . $menus['menu_icon'] . '"></i> <span>' . $menus['menu_name'] . '</span></a></li>';
                        } else {
                            $sub_menus .= Helper::getSubmenus($menus, $parent_menus);
                        }
                        
                    }

        $sub_menus .= 
                '</ul>
            </li>';
        return $sub_menus;
    }
}
