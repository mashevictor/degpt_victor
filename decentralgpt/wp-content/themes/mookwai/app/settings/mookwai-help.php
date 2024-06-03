<?php

/**
 * MooKwai theme options - help
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\App\Settings;

defined('ABSPATH') || exit;

use MOOKWAI\App\Traits\Singleton;

class MooKwai_Help
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
      __('Get Help', 'mookwai'),
      __('Get Help', 'mookwai'),
      'manage_options',
      'mookwai-help',
      [$this, 'outputHTML'],
      2
    );
    $pageHook = add_submenu_page(
      '',
      __('Get Help', 'mookwai'),
      __('Get Help', 'mookwai'),
      'manage_options',
      'mookwai-help',
      [$this, 'outputHTML'],
      2
    );
    add_action("admin_footer", [$this, 'mookwai_menu_help_blank']);
    add_action("load-{$pageHook}", [$this, 'mookwai_help_redirect_link']);
  }

  public function mookwai_menu_help_blank()
  {
?>
    <script type="text/javascript">
      const helpLink = document.querySelector('#mookwai-help')?.parentElement;
      helpLink?.setAttribute('target', '_blank');
    </script>
  <?php
  }

  public function mookwai_help_redirect_link()
  {
  ?>
    <script type="text/javascript">
      window.location.replace(
        'https://mookwai.com/document/getting-started/preparation/'
      );
    </script>
  <?php
  }

  public static function outputHTML()
  { ?>
    <div></div>
<?php }
}
