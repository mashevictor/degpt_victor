<?php

/**
 * MooKwai theme options - generate mookwai icon library
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\App\Settings;

defined('ABSPATH') || exit;

use MOOKWAI\App\Traits\Singleton;

class MooKwai_Icon_Library
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
    add_submenu_page(
      'mookwai-settings',
      esc_html__('Generate Icons', 'mookwai'),
      esc_html__('Generate Icons', 'mookwai'),
      'manage_options',
      'mookwai-generate-icon-library',
      [$this, 'outputHTML'],
      100
    );
    $publicPageHook = add_submenu_page(
      '',
      esc_html__('Generate Icons', 'mookwai'),
      esc_html__('Generate Icons', 'mookwai'),
      'manage_options',
      'mookwai-generate-icon-library',
      [$this, 'outputHTML'],
      100
    );
    add_action("load-{$publicPageHook}", [$this, 'mookwaiGenerateIconLibrary']);
  }

  public function mookwaiGenerateIconLibrary()
  {
    include(MOOKWAI_PATH . '/app/settings/generate-icon-library.php');
    $fileMooKwaiIconJson = fopen(MOOKWAI_PATH . '/icon-library/mookwai-icons.json', 'w');
    if (flock($fileMooKwaiIconJson, LOCK_EX)) {
      fwrite($fileMooKwaiIconJson, $MooKwaiIconContent);
    }
    fclose($fileMooKwaiIconJson);
  }

  public static function outputHTML()
  { ?>
    <div></div>
<?php }
}
