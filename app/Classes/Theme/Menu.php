<?php

namespace App\Classes\Theme;

use App\Models\MenuManager;
use App\Models\Theme;
use Illuminate\Support\Facades\Auth;

class Menu
{

  public static function sidebar()
  {
    $menuManager = new MenuManager;
    $roleId = isset(Auth::user()->roles) ? Auth::user()->roles->id : NULL;
    $menu_list = $menuManager->getall($roleId);
    $roots = $menu_list->where('parent_id', 0) ?? array();
    return self::tree($roots, $menu_list, $roleId);
  }

//    public static function theme()
//    {
//        $theme = Theme::select('direction', 'sidebar_layout', 'sidemenu', 'theme_layout', 'sidebar_color')->where('user_id', Auth::id())->first() ?? NULL;
//        $value = array_values((isset($theme) ? $theme->toArray() : array()));
//        return implode(" ", $value);
//    }

  public static function tree($roots, $menu_list, $roleId, $parentId = 0)
  {
    $html = "";
    foreach ($roots as $item) {
      $find = $menu_list->where('parent_id', $item['id']);
      if ($parentId == 0) {
        if ($find->count() > 0) {
          $html .=
            "
            <li class='slide'>
              <a class='side-menu__item' data-bs-toggle='slide' href='" . (!$item->menu_permission_id ? ($item->path_url ? $item->path_url : '#') : $item->menupermission->path_url) . "'>
                <i class='side-menu__icon " . (!$item->menu_permission_id ? $item->icon : $item->menupermission->icon) . "'></i>
                <span class='" . ($parentId == 0 ? 'side-menu__label' : NULL) . "'>" . (!$item->menu_permission_id ? $item->title : $item->menupermission->title) . "</span>
                <i class='angle fe fe-chevron-right'></i>
              </a>
            ";
          $html .= self::children($find, $menu_list, $roleId, $item['id']);
          $html .= '</li>';
        } else {
          $html .= '
            <li class="slide">
              <a class="side-menu__item" data-bs-toggle="slide" href="' . (!$item->menu_permission_id ? ($item->path_url ? $item->path_url : '#') : $item->menupermission->path_url) . '">
                <i class="side-menu__icon ' . (!$item->menu_permission_id ? $item->icon : $item->menupermission->icon) . '"></i>
                <span class="side-menu__label">' . (!$item->menu_permission_id ? $item->title : $item->menupermission->title) . '</span>
              </a>
            </li>
          ';
        }
      }
    }
    return $html;
  }

  public static function children($roots, $menu_list, $roleId, $parentId = 0)
  {
    $html = '<ul class="slide-menu">';
    foreach ($roots as $item) {
      $find = $menu_list->where('parent_id', $item['id']);
      if ($find->count() > 0) {
        $htmlChildren = self::children($find, $menu_list, $roleId, $item['id']);
        $html .= '
        <li class="nav-sub-item">
          <a class="nav-sub-link with-sub" href="' . (!$item->menu_permission_id ? ($item->path_url ? $item->path_url : '#') : $item->menupermission->path_url) . '">
            <span class="' . ($parentId == 0 ? 'side-menu__label' : NULL) . '">' . (!$item->menu_permission_id ? $item->title : $item->menupermission->title) . '</span>
            <i class="angle fe fe-chevron-right"></i>
          </a>
          ' . $htmlChildren . '
        </li>';
      } else {
        $html .= '
        <li>
           <a class="slide-item" href="' . ($find->count() > 0 ? "javascript: void(0);" : (!$item->menu_permission_id ? ($item->path_url ? $item->path_url : '#') : $item->menupermission->path_url)) . '" class="nav-sub-link">
              ' . (!$item->menu_permission_id ? $item->title : $item->menupermission->title) . '
           </a>
        </li>';
      }
    }
    $html .= '</ul>';
    return $html;
  }

}
