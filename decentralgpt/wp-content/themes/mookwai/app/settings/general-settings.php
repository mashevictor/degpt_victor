<?php

/**
 * MooKwai theme options - setting
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\App\Settings;

defined('ABSPATH') || exit;

use MOOKWAI\App\Traits\Singleton;

class General_Settings extends MooKwai_Controllers
{

  use Singleton;

  protected function __construct()
  {
    $this->setup_hooks();
  }

  protected function setup_hooks()
  {
    if (is_admin()) {
      add_action('admin_menu', [$this, 'add_admin_menu']);
    }
  }

  public function add_admin_menu()
  {
    add_menu_page(
      esc_html__('General Settings', 'mookwai'),
      'MooKwai',
      'manage_options',
      'mookwai-settings',
      [$this, 'pageHTML'],
      'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDI2LjAuMiwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPgo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IuWbvuWxgl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCIKCSB2aWV3Qm94PSIwIDAgMzIgMzIiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDMyIDMyOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxnPgoJPHBhdGggZD0iTTIsMnYyOGgyOFYySDJ6IE0yNi4xLDIyLjhjMCwwLjEtMC4xLDAuMS0wLjEsMC4xaC0yLjJjLTAuMSwwLTAuMS0wLjEtMC4xLTAuMVY5LjVjMC0wLjEtMC4xLTAuMS0wLjIsMGwtNS44LDEzLjMKCQljMCwwLjEtMC4xLDAuMS0wLjIsMC4xaC0yLjhjLTAuMSwwLTAuMi0wLjEtMC4yLTAuMUw4LjYsOS41YzAtMC4xLTAuMi0wLjEtMC4yLDB2MTMuM2MwLDAuMS0wLjEsMC4xLTAuMSwwLjFINi4xCgkJYy0wLjEsMC0wLjEtMC4xLTAuMS0wLjFWOS4yYzAtMC4xLDAuMS0wLjEsMC4xLTAuMUgxMWMwLjEsMCwwLjIsMC4xLDAuMiwwLjFMMTUuOSwyMGMwLDAuMSwwLjEsMC4xLDAuMSwwbDQuNy0xMC44CgkJYzAtMC4xLDAuMS0wLjEsMC4yLTAuMWg0LjljMC4xLDAsMC4xLDAuMSwwLjEsMC4xVjIyLjh6Ii8+CjwvZz4KPC9zdmc+Cg==',
      100
    );
    $pageHook = add_submenu_page(
      'mookwai-settings',
      esc_html__('General Settings', 'mookwai'),
      esc_html__('Settings', 'mookwai'),
      'manage_options',
      'mookwai-settings',
      [$this, 'pageHTML'],
      1
    );
    add_action("load-{$pageHook}", [$this, 'mookwai_general_setting_enquee_scripts']);
  }

  public function mookwai_general_setting_enquee_scripts()
  {
    wp_enqueue_media();
    wp_enqueue_style(
      'mk-dashboard',
      untrailingslashit(get_template_directory_uri()) . '/assets/css/mk-dashboard.css',
      ['wp-edit-blocks'],
      filemtime(MOOKWAI_PATH . '/assets/css/mk-dashboard.css')
    );
    wp_enqueue_script(
      'mkd-vendors',
      untrailingslashit(get_template_directory_uri()) . '/vendors/core.dll.js',
      null,
      filemtime(MOOKWAI_PATH . '/vendors/core.dll.js'),
      true
    );
    wp_enqueue_script(
      'mk-dashboard',
      untrailingslashit(get_template_directory_uri()) . '/assets/js/mk-dashboard.js',
      ['wp-editor', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-api', 'wp-edit-post', 'media-views', 'wp-components', 'wp-mediaelement', 'mkd-vendors'],
      filemtime(MOOKWAI_PATH . '/assets/js/mk-dashboard.js'),
      true
    );
    wp_localize_script('mk-dashboard', 'mookwaiData', array(
      'root_url' => get_site_url(),
      'nonce' => wp_create_nonce('wp_rest')
    ));
    wp_set_script_translations('mk-dashboard', 'mookwai', untrailingslashit(get_template_directory()) . '/languages');
  }

  public static function pageHTML()
  {}
}
