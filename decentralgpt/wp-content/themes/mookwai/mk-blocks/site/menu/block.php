<?php

/**
 * MooKwai blocks
 * Site - Menu
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\MK_Blocks\Site;

use MOOKWAI\App\Traits\Singleton;

class Menu
{

  use Singleton;

  protected function __construct()
  {
    $this->setup_hooks();
  }

  protected function setup_hooks()
  {
    add_action('init', [$this, 'registerBlock']);
  }

  public function registerBlock()
  {
    register_block_type_from_metadata(__DIR__, array(
      'render_callback' => [$this, 'outputData']
    ));
  }

  public function outputData($attributes)
  {
    if (!$attributes['menuSlug']) return '';
    global $post;
    $post_id = null;
    $the_query_obj = get_queried_object();
    if ($the_query_obj) {
      $post_id = $the_query_obj->ID;
    } else if ($post) {
      $post_id = $post->ID;
    }
    $menu_items = '';
    $menu = get_term($attributes['menuSlug'], 'nav_menu');
    $icon_path = MOOKWAI_PATH . '/assets/icon-library/source.json';
    $mookwai_library = json_decode(file_get_contents($icon_path));
    $the_icons = $mookwai_library->icons;
    $icon_data = $attributes['iconChild'];
    $icon_slug = $icon_data['slug'];
    $icon_size = $icon_data['size'];
    $theColumn = array_column($the_icons, 'slug');
    $found = array_search($icon_slug, $theColumn);
    $the_icon = $the_icons[$found]->$icon_size;
    $getMenuItems = wp_get_nav_menu_items($menu->term_id);
    if ($getMenuItems && count($getMenuItems)) {
      $parentArray = [];
      foreach ($getMenuItems as $value) {
        array_push($parentArray, $value->menu_item_parent);
      }
      $parentArray = array_unique($parentArray);
      $menuArray = [];
      if ($parentArray && count($parentArray)) {
        $isBlank = '';
        if ($attributes['openInNew']) $isBlank = ' target="_blank"';
        foreach ($parentArray as $parentValue) {
          $theArray = [];
          foreach ($getMenuItems as $getItemValue) {
            if ($getItemValue->menu_item_parent === $parentValue) {
              $hasChild = false;
              $i = 0;
              if (array_search($getItemValue->ID, $parentArray)) {
                $hasChild = true;
              }
              $theValue = array(
                'id' => $getItemValue->ID,
                'type' => $getItemValue->type,
                'object_id' => $getItemValue->object_id,
                'title' => $getItemValue->title,
                'classes' => $getItemValue->classes,
                'url' => $getItemValue->url,
                'has_child' => $hasChild
              );
              array_push($theArray, $theValue);
            }
          }
          $result = ['parent' => $parentValue, 'content' => $theArray];
          array_push($menuArray, $result);
        }
        if ($menuArray && count($menuArray)) {
          $is_interactive = '';
          if ($attributes['isInteractive']) $is_interactive = ' aria-expanded="true"';
          $foundRoot = array_filter($menuArray, function ($ar) {
            return ($ar['parent'] == 0);
          });
          foreach ($foundRoot[0]['content'] as $item) {
            $menu_items .= $this->generateItem($item, $menuArray, $post_id, $the_icon, $isBlank, $is_interactive, true);
          }
        }
      }
    }

    if ($menu_items) {
      $menu_items = sprintf(
        '<ul class="el-menus-list">%1$s</ul>',
        $menu_items,
      );
    }
    $tagName = $attributes['tagName'];

    // Block props
    $wrapper_attributes = '';
    $wrapper_classes = ['mk-site-menu'];
    if (isset($attributes['globalSlug']) && $attributes['globalSlug']) {
      $wrapper_classes[] = $attributes['globalSlug'];
    }
    if (isset($attributes['blockID']) && $attributes['blockID']) {
      $wrapper_classes[] = $attributes['blockID'];
    }
    if (isset($attributes['isInteractive']) && $attributes['isInteractive']) {
      $wrapper_classes[] = 'hide-sub';
    }
    $custom_classes = isset($attributes['cssClass']) ? $attributes['cssClass'] : [];
    if (!empty($custom_classes)) {
      foreach ($custom_classes as $item) {
        $wrapper_classes[] = $item;
      }
    }
    $wrapper_attributes .= 'class="' . esc_attr(implode(' ', $wrapper_classes)) . '"';
    $custom_attrs = isset($attributes['htmlAttr']) ? $attributes['htmlAttr'] : [];
    $wrapper_attrs = [];
    if (!empty($custom_attrs)) {
      foreach ($custom_attrs as $item) {
        foreach ($item as $key => $value) {
          $wrapper_attrs[] = $key . '=' . $value;
        }
      }
    }
    $wrapper_attributes .= ' ' . esc_attr(implode(' ', $wrapper_attrs));

    return sprintf(
      '<%1$s %2$s>%3$s</%1$s>',
      $tagName,
      $wrapper_attributes,
      $menu_items
    );
  }

  protected function generateItem($item, $menuArray, $obj_id, $the_icon, $isBlank, $is_interactive, $isRoot)
  {
    $theHTML = '';
    $title = $item['title'];
    $url = $item['url'];
    $theId = $item['id'];
    $object_id = $item['object_id'];
    $type = $item['type'];
    $classes = ['el-menu-item'];
    foreach ($item['classes'] as $class_item) {
      if ($class_item) $classes[] = $class_item;
    }
    $idName = "menu-item-$theId";
    if (is_front_page()) {
      $current_url = $item['url'];
      $the_site_url = rtrim(get_option('siteurl'), '/');
      if (substr($current_url, -1) === '/') {
        $current_url = rtrim($current_url, '/');
      }
      if ($current_url == $the_site_url) {
        $classes[] = 'current';
      }
    }
    if ($object_id == $obj_id && $type === 'post_type') {
      $classes[] = 'current';
    }
    if (!$item['has_child']) {
      $the_classes = implode(' ', $classes);
      $theHTML .= "<li id='$idName' class='{$the_classes}'><a href='$url'$isBlank>$title</a></li>";
      return $theHTML;
    }
    $classes[] = 'has-child';
    $the_classes = implode(' ', $classes);
    if ($isRoot) {
      $theHTML .= "<li id='$idName' class='{$the_classes}'><a href='$url'{$isBlank}{$is_interactive}>$title<span class='icon icon-sub'>$the_icon</span></a><div class='el-sub-panel'><ul class='sub-menus'>";
    } else {
      $theHTML .= "<li id='$idName' class='{$the_classes}'><a href='$url'$isBlank>$title</a><ul class='sub-menus'>";
    }
    $theColumn = array_column($menuArray, 'parent');
    $foundIndex = array_search($theId, $theColumn);
    $foundTarget = $menuArray[$foundIndex];
    if ($foundTarget && count($foundTarget['content'])) {
      foreach ($foundTarget['content'] as $targetItem) {
        $theHTML .= $this->generateItem($targetItem, $menuArray, $obj_id, false, $isBlank, '', false);
      }
    }
    if ($isRoot) {
      $theHTML .= "</ul></div></li>";
    } else {
      $theHTML .= "</ul></li>";
    }

    return $theHTML;
  }
}
