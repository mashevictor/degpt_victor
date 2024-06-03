<?php

/**
 * MooKwai blocks
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\App;

defined('ABSPATH') || exit;

use MOOKWAI\App\Traits\Singleton;

class MooKwai_Blocks
{

  use Singleton;

  protected function __construct()
  {
    \MOOKWAI\MK_Blocks\Structure\Section::get_instance();
    \MOOKWAI\MK_Blocks\Structure\Container::get_instance();
    \MOOKWAI\MK_Blocks\Structure\Column::get_instance();
    \MOOKWAI\MK_Blocks\Structure\Box::get_instance();

    \MOOKWAI\MK_Blocks\Basic\Button::get_instance();
    \MOOKWAI\MK_Blocks\Basic\Text::get_instance();
    \MOOKWAI\MK_Blocks\Basic\Icon::get_instance();
    \MOOKWAI\MK_Blocks\Basic\Image::get_instance();
    \MOOKWAI\MK_Blocks\Basic\Svg::get_instance();
    \MOOKWAI\MK_Blocks\Basic\Canvas::get_instance();
    \MOOKWAI\MK_Blocks\Basic\Lottie::get_instance();

    \MOOKWAI\MK_Blocks\Extention\Navigation::get_instance();
    \MOOKWAI\MK_Blocks\Extention\Table_Of_Contents::get_instance();
    \MOOKWAI\MK_Blocks\Extention\Carousel::get_instance();
    \MOOKWAI\MK_Blocks\Extention\Carousel_Container::get_instance();
    \MOOKWAI\MK_Blocks\Extention\Carousel_Item::get_instance();
    \MOOKWAI\MK_Blocks\Extention\Slides::get_instance();
    \MOOKWAI\MK_Blocks\Extention\Slides_Container::get_instance();
    \MOOKWAI\MK_Blocks\Extention\Slides_Item::get_instance();
    \MOOKWAI\MK_Blocks\Extention\Slider_Pagination::get_instance();
    \MOOKWAI\MK_Blocks\Extention\Slider_Toggle_Group::get_instance();
    \MOOKWAI\MK_Blocks\Extention\Slider_Toggle_Prev::get_instance();
    \MOOKWAI\MK_Blocks\Extention\Slider_Toggle_Next::get_instance();
    \MOOKWAI\MK_Blocks\Extention\Collapse::get_instance();
    \MOOKWAI\MK_Blocks\Extention\Collapse_Item::get_instance();
    \MOOKWAI\MK_Blocks\Extention\Flip_Card::get_instance();
    \MOOKWAI\MK_Blocks\Extention\Flip_Card_Front::get_instance();
    \MOOKWAI\MK_Blocks\Extention\Flip_Card_Back::get_instance();
    \MOOKWAI\MK_Blocks\Extention\Smoother::get_instance();
    \MOOKWAI\MK_Blocks\Extention\Horizontal::get_instance();
    \MOOKWAI\MK_Blocks\Extention\Horizontal_Container::get_instance();
    \MOOKWAI\MK_Blocks\Extention\Horizontal_Item::get_instance();
    \MOOKWAI\MK_Blocks\Extention\Marquee::get_instance();
    \MOOKWAI\MK_Blocks\Extention\Typewriter::get_instance();

    \MOOKWAI\MK_Blocks\Site\Search::get_instance();
    \MOOKWAI\MK_Blocks\Site\Menu::get_instance();
    \MOOKWAI\MK_Blocks\Site\Breadcrumbs::get_instance();
    \MOOKWAI\MK_Blocks\Site\Post_Content::get_instance();
    \MOOKWAI\MK_Blocks\Site\Post_Query::get_instance();
    \MOOKWAI\MK_Blocks\Site\Post_List::get_instance();
    \MOOKWAI\MK_Blocks\Site\Post_Title::get_instance();
    \MOOKWAI\MK_Blocks\Site\Post_Excerpt::get_instance();
    \MOOKWAI\MK_Blocks\Site\Post_Cover::get_instance();
    \MOOKWAI\MK_Blocks\Site\Post_Terms::get_instance();
    \MOOKWAI\MK_Blocks\Site\Post_Date::get_instance();
    \MOOKWAI\MK_Blocks\Site\Post_Link::get_instance();
    \MOOKWAI\MK_Blocks\Site\Pagination::get_instance();
    \MOOKWAI\MK_Blocks\Site\Pagination_Number::get_instance();
    \MOOKWAI\MK_Blocks\Site\Pagination_Previous::get_instance();
    \MOOKWAI\MK_Blocks\Site\Pagination_Next::get_instance();
    \MOOKWAI\MK_Blocks\Site\Post_Empty::get_instance();

    $this->setup_hooks();
  }

  protected function setup_hooks()
  {
    add_action('after_setup_theme', [$this, 'mookwai_remove_core_patterns']);
    add_filter('block_categories_all', [$this, 'mookwai_block_categories']);
  }

  function mookwai_remove_core_patterns()
  {
    remove_theme_support('core-block-patterns');
  }
  
  public static function mookwai_block_categories($block_categories)
  {
    $new_array = [];
    array_push(
      $new_array,
      array(
        'slug'  => 'mk-b-cat-layout',
        'title' => __('Structure', 'mookwai'),
        'icon'  => null,
      )
    );
    array_push(
      $new_array,
      array(
        'slug'  => 'mk-b-cat-basic',
        'title' => __('Basic', 'mookwai'),
        'icon'  => null,
      )
    );
    array_push(
      $new_array,
      array(
        'slug'  => 'mk-b-cat-extention',
        'title' => __('Extention', 'mookwai'),
        'icon'  => null,
      )
    );
    array_push(
      $new_array,
      array(
        'slug'  => 'mk-b-cat-site',
        'title' => __('Site Content', 'mookwai'),
        'icon'  => null,
      )
    );
    foreach ($block_categories as $category) {
      array_push(
        $new_array,
        $category
      );
    }
    return $new_array;
  }

}
