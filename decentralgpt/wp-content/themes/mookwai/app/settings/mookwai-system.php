<?php

/**
 * MooKwai theme options - system
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\App\Settings;

defined('ABSPATH') || exit;

use MOOKWAI\App\Traits\Singleton;

class MooKwai_System
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
      __('System', 'mookwai'),
      __('System', 'mookwai'),
      'manage_options',
      'mookwai-system',
      [$this, 'mookwai_menu_system_page'],
      1
    );
    $pageHook = add_submenu_page(
      '',
      __('System', 'mookwai'),
      __('System', 'mookwai'),
      'manage_options',
      'mookwai-system',
      [$this, 'mookwai_menu_system_page'],
      1
    );
    add_action("load-{$pageHook}", [$this, 'mookwai_system_enquee_scripts']);
  }

  public function mookwai_system_enquee_scripts()
  {
    wp_enqueue_style(
      'mkd-general-setting',
      untrailingslashit(get_template_directory_uri()) . '/app/settings/general-settings.css',
      ['wp-edit-blocks'],
      filemtime(MOOKWAI_PATH . '/app/settings/general-settings.css')
    );
  }

  public static function mookwai_menu_system_page()
  {
    $succss_asset = '<img class="mk-status-icon" src="' . get_template_directory_uri() . '/assets/images/icon-success.png' . '" />';
    $error_asset = '<img class="mk-status-icon" src="' . get_template_directory_uri() . '/assets/images/icon-error.png' . '" />';

    // WordPress 版本
    $wordpress_version = get_bloginfo('version');
    $numeric_wordpress_version = floatval($wordpress_version);
    $wordpress_version_content = sprintf(
      '<div class="mk-status__item-content">%1$s%2$s</div>',
      $succss_asset,
      $wordpress_version
    );
    if ($numeric_wordpress_version < 6.5) {
      $wordpress_version_content = sprintf(
        '<div class="mk-status__item-content mk-error">%1$s%2$s，请将WordPress升级到6.5或以上版本。</div>',
        $error_asset,
        $wordpress_version
      );
    }

    // MooKwai 版本
    $theme = wp_get_theme()->parent();
    if (!$theme) $theme = wp_get_theme();
    $theme_version = $theme->get('Version');

    // 固定链接
    $permalink_structure = get_option('permalink_structure');
    $permalink_content = sprintf(
      '<div class="mk-status__item-content">%1$s已配置</div>',
      $succss_asset,
    );
    if (!$permalink_structure) {
      $permalink_content = sprintf(
        '<div class="mk-status__item-content mk-error">%1$s未配置，需将固定链接形式修改为非朴素形式。 <a href="%2$s">前去配置</a></div>',
        $error_asset,
        admin_url('options-permalink.php'),
      );
    }

    // PHP 版本
    $os_info = php_uname();
    $php_version = phpversion();
    $numeric_php_version = floatval($php_version);
    $php_version_content = sprintf(
      '<div class="mk-status__item-content">%1$s%2$s</div>',
      $succss_asset,
      $php_version
    );
    if ($numeric_php_version < 7.4) {
      $php_version_content = sprintf(
        '<div class="mk-status__item-content mk-error">%1$s%2$s，请将PHP升级到7.4或以上版本。</div>',
        $error_asset,
        $php_version
      );
    }
    $php_gd_content = 'No';
    $php_zip_content = 'No';
    if (extension_loaded('gd')) {
      $php_gd_content = 'Yes';
    }
    if (extension_loaded('zip')) {
      $php_zip_content = 'Yes';
    }
?>
    <div class="wrap">
      <h1><?php _e('MooKwai: System', 'mookwai'); ?></h1>
      <div class="mk-status-group">
        <div class="mk-status-group__head"><?php _e('Site Info', 'mookwai'); ?></div>
        <div class="mk-status-group__body">
          <div class="mk-status__item">
            <div class="mk-status__item-title"><?php _e('Site Name', 'mookwai'); ?></div>
            <div class="mk-status__item-content"><?php print get_bloginfo('name'); ?></div>
          </div>
          <div class="mk-status__item">
            <div class="mk-status__item-title"><?php _e('Site URL', 'mookwai'); ?></div>
            <div class="mk-status__item-content"><?php print home_url(); ?></div>
          </div>
          <div class="mk-status__item">
            <div class="mk-status__item-title"><?php _e('WordPress Version', 'mookwai'); ?></div>
            <?php echo $wordpress_version_content; ?>
          </div>
          <div class="mk-status__item">
            <div class="mk-status__item-title"><?php _e('MooKwai Version', 'mookwai'); ?></div>
            <div class="mk-status__item-content"><?php echo $theme_version; ?></div>
          </div>
          <div class="mk-status__item">
            <div class="mk-status__item-title"><?php _e('Language', 'mookwai'); ?></div>
            <div class="mk-status__item-content"><?php print get_bloginfo('language'); ?></div>
          </div>
          <div class="mk-status__item">
            <div class="mk-status__item-title"><?php _e('Permalink', 'mookwai'); ?></div>
            <?php echo $permalink_content; ?>
          </div>
        </div>
      </div>
      <div class="mk-status-group">
        <div class="mk-status-group__head"><?php _e('PHP Info', 'mookwai'); ?></div>
        <div class="mk-status-group__body">
          <div class="mk-status__item">
            <div class="mk-status__item-title">Operating System</div>
            <div class="mk-status__item-content"><?php echo $os_info ?></div>
          </div>
          <div class="mk-status__item">
            <div class="mk-status__item-title"><?php _e('PHP Version', 'mookwai'); ?></div>
            <?php echo $php_version_content; ?>
          </div>
          <div class="mk-status__item">
            <div class="mk-status__item-title">post_max_size</div>
            <div class="mk-status__item-content"><?php print ini_get('post_max_size'); ?></div>
          </div>
          <div class="mk-status__item">
            <div class="mk-status__item-title">max_execution_time</div>
            <div class="mk-status__item-content"><?php print ini_get('max_execution_time'); ?></div>
          </div>
          <div class="mk-status__item">
            <div class="mk-status__item-title">max_input_vars</div>
            <div class="mk-status__item-content"><?php print ini_get('max_input_vars'); ?></div>
          </div>
          <div class="mk-status__item">
            <div class="mk-status__item-title">upload_max_filesize</div>
            <div class="mk-status__item-content"><?php print ini_get('upload_max_filesize'); ?></div>
          </div>
          <div class="mk-status__item">
            <div class="mk-status__item-title">GD Installed</div>
            <div class="mk-status__item-content"><?php echo $php_gd_content ?></div>
          </div>
          <div class="mk-status__item">
            <div class="mk-status__item-title">ZIP Installed</div>
            <div class="mk-status__item-content"><?php echo $php_zip_content ?></div>
          </div>
        </div>
      </div>
    </div>
<?php }
}
