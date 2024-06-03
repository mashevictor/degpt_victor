<?php

/**
 * MooKwai theme setup
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\App;

defined('ABSPATH') || exit;

use MOOKWAI\App\Traits\Singleton;

class MooKwai_Setup
{

  use Singleton;

  protected function __construct()
  {
    MooKwai_Database::get_instance();
    MooKwai_Head::get_instance();
    MooKwai_Scripts::get_instance();
    MooKwai_Blocks::get_instance();
    MooKwai_Reset::get_instance();
    MooKwai_Metas::get_instance();
    if (get_option('mkopt_svg_upload')) {
      MooKwai_Svg::get_instance();
    }
    Settings\General_Settings::get_instance();
    Settings\MooKwai_System::get_instance();
    Settings\MooKwai_Help::get_instance();
    Routes\MooKwai_Global_Styles_Route::get_instance();
    Routes\MooKwai_Global_Styles_Example_Route::get_instance();
    Routes\MooKwai_Post_Settings_Route::get_instance();
    Routes\MooKwai_Icon_Library_Route::get_instance();
    Routes\MooKwai_Search_Link_Route::get_instance();
    Routes\MooKwai_Dashboard_Route::get_instance();

    $this->setup_hooks();
  }

  protected function setup_hooks()
  {
    add_action('after_setup_theme', [$this, 'setup_theme']);
    if (get_option('mkopt_admin_access')) {
      add_action('admin_init', [$this, 'mookwai_restrict_admin_access']);
    }
    add_filter('template_include', [$this, 'mookwai_template_include']);
}

  public static function setup_theme()
  {
    //add_theme_support('title-tag');

    add_theme_support('post-thumbnails');

    add_theme_support(
      'html5',
      array(
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
        'navigation-widgets',
      )
    );

    add_theme_support(
      'custom-logo',
      array(
        'height' => 300,
        'width' => 300,
        'flex-height' => true,
        'flex-width' => true,
        'header-text' => array('site-title', 'site-description'),
        'unlink-homepage-logo' => true,
      )
    );

    register_nav_menus(
      array(
        'primary' => esc_html__('Primary menu', 'mookwai'),
        'secondary'  => esc_html__('Secondary menu', 'mookwai'),
      )
    );

    if (get_option('mkopt_disable_lazyload', '0')) {
      add_filter('wp_lazy_loading_enabled', '__return_false');
    }

    add_theme_support('responsive-embeds');

  }

  public static function mookwai_restrict_admin_access()
  {
    if (is_admin() && !current_user_can('manage_options') && !current_user_can('edit_others_posts') && !wp_doing_ajax()) {
      wp_redirect(home_url());
      exit;
    }
  }

  public static function mookwai_template_include($template)
  {
    $template = locate_template('template-canvas.php');
    return $template;
  }
}
