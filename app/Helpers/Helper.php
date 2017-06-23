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
                            $sub_menus .= '<li><a data-menu-id="'. $menus['id'] .'" href="' . \URL::to($menus['menu_link']) . '"><i class="fa fa-' . $menus['menu_icon'] . '"></i> <span>' . $menus['menu_name'] . '</span></a></li>';
                        } else {
                            $sub_menus .= Helper::getSubmenus($menus, $parent_menus);
                        }
                        
                    }

        $sub_menus .= 
                '</ul>
            </li>';
        return $sub_menus;
    }

    public static function checkRoleAndGetDate ($start_date, $end_date) {
    	$role = \Auth::User()->roles->role_name;
			if ($role != 'admin') {
				$report_start_date = \Config::get('constants.REPORT_START_DATE');
				$report_end_date = \Config::get('constants.REPORT_END_DATE');
				if ($start_date < $report_start_date || $start_date > $report_end_date) {
					$start_date = $report_start_date;
				}
				if ($end_date < $report_start_date || $end_date > $report_end_date) {
					$end_date = $report_end_date;
				}
				return [ "admin_role" => false, "start_date" => $start_date, "end_date" => $end_date ];
			}
			return [ "admin_role" => true ];
    }
}
