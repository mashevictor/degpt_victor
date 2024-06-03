<?php

/**
 * MooKwai enqueue assets
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\App;

defined('ABSPATH') || exit;

use MOOKWAI\App\Traits\Singleton;

class MooKwai_Scripts
{
  use Singleton;

  protected function __construct()
  {
    $this->setup_hooks();
  }

  protected function setup_hooks()
  {
    add_action('wp_enqueue_scripts', [$this, 'register_assets'], 100);
    add_action('enqueue_block_assets', [$this, 'register_block_editor_assets'], 100);
    add_action('init', [$this, 'remove_global_css']);
    add_filter("script_loader_tag", [$this, 'add_module_to_my_script'], 10, 3);
    add_action('wp_footer', [$this, 'mookwai_page_import_script_file'], 100);
    add_action('after_setup_theme', [$this, 'mookwai_textdomain']);
  }

  // 注册前端代码
  public function register_assets($post_id)
  {
    // Get datas
    $mkopt_enqueue_jquery = get_option('mkopt_enqueue_jquery');
    $mkopt_enqueue_woocommerce = get_option('mkopt_enqueue_woocommerce');
    // $mkopt_enqueue_spline = get_option('mkopt_enqueue_spline', '1');
    $mkopt_enqueue_three = get_option('mkopt_enqueue_three');
    $mkopt_enqueue_barba = get_option('mkopt_enqueue_barba');
    $mkopt_enqueue_default = get_option('mkopt_enqueue_default_styles', '1');
    $mkopt_enqueue_extention_scripts = get_option('mkopt_enqueue_extention_scripts', '1');

    $registered_styles = wp_styles()->registered;
    $block_styles = preg_grep('/^wp-block-/', array_keys($registered_styles));
    foreach ($block_styles as $style_handle) {
      wp_dequeue_style($style_handle);
    }

    // Reset
    wp_dequeue_style('global-styles');
    wp_dequeue_style('wp-block-heading');
    wp_dequeue_style('wp-block-paragraph');
    wp_dequeue_style('core-block-supports');
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('global-styles-inline-css');
    wp_dequeue_style('mk-editor-control-ui-styles');
    wp_dequeue_style('mk-editor-global-styles');
    wp_dequeue_style('mk-editor-default-styles');

    if ($mkopt_enqueue_jquery !== '1') {
      wp_deregister_script('jquery');
    }

    // MooKwai scripts
    wp_register_script(
      'vendors-core',
      get_theme_file_uri('/vendors/core.dll.js'),
      null,
      filemtime(get_template_directory() . '/vendors/core.dll.js'),
      true
    );
    wp_enqueue_script('vendors-core');

    if ($mkopt_enqueue_three === '1') {
      wp_register_script(
        'vendors-three',
        get_theme_file_uri('/vendors/three.dll.js'),
        array('vendors-core'),
        filemtime(get_template_directory() . '/vendors/three.dll.js'),
        true
      );
      wp_enqueue_script('vendors-three');
    }

    if ($mkopt_enqueue_barba === '1') {
      wp_register_script(
        'vendors-barba',
        get_theme_file_uri('/vendors/barba.dll.js'),
        array('vendors-core'),
        filemtime(get_template_directory() . '/vendors/barba.dll.js'),
        true
      );
      wp_enqueue_script('vendors-barba');
    }
    
    wp_register_script(
      'preload',
      get_theme_file_uri('/assets/js/preloadjs.min.js'),
      null,
      wp_get_theme()->get('Version'),
      true
    );
    wp_enqueue_script('preload');

    // if ($mkopt_enqueue_spline === '1') {
    //   wp_register_script(
    //     'spline',
    //     'https://unpkg.com/@splinetool/viewer/build/spline-viewer.js',
    //     null,
    //     wp_get_theme()->get('Version'),
    //     true
    //   );
    //   wp_enqueue_script('spline');
    // }

    if ($mkopt_enqueue_default === '1') {
      wp_register_style(
        'mk-default-styles',
        get_theme_file_uri('/assets/css/mk-default.css'),
        null,
        filemtime(get_template_directory() . '/assets/css/mk-default.css'),
        'all'
      );
      wp_enqueue_style('mk-default-styles');
    }

    if ($mkopt_enqueue_extention_scripts === '1') {
      wp_register_script(
        'mk-extentions',
        get_theme_file_uri('/assets/js/mk-extentions.min.js'),
        array('vendors-core'),
        filemtime(get_template_directory() . '/assets/js/mk-extentions.min.js'),
        true
      );
      wp_enqueue_script('mk-extentions');
    }

    if (file_exists(MOOKWAI_CONTENT_PATH . "/mk-scripts/mk-global.min.css")) {
      wp_register_style(
        'mk-global-styles',
        esc_url(home_url("/wp-content/uploads/mk-scripts/mk-global.min.css")),
        null,
        filemtime(MOOKWAI_CONTENT_PATH . "/mk-scripts/mk-global.min.css"),
        'all'
      );
      wp_register_script(
        'mk-global-script',
        esc_url(home_url("/wp-content/uploads/mk-scripts/mk-global.min.js")),
        array('vendors-core', 'mk-extentions'),
        filemtime(MOOKWAI_CONTENT_PATH . "/mk-scripts/mk-global.min.js"),
        true
      );
      wp_enqueue_style('mk-global-styles');
      wp_enqueue_script('mk-global-script');
    }

    if (file_exists(MOOKWAI_CONTENT_PATH . "/mk-scripts/mk-template.min.css")) {
      wp_register_style(
        'mk-template-styles',
        esc_url(home_url("/wp-content/uploads/mk-scripts/mk-template.min.css")),
        array('mk-global-styles'),
        filemtime(MOOKWAI_CONTENT_PATH . "/mk-scripts/mk-template.min.css"),
        'all'
      );
      wp_register_script(
        'mk-template-script',
        esc_url(home_url("/wp-content/uploads/mk-scripts/mk-template.min.js")),
        array('vendors-core', 'mk-extentions'),
        filemtime(MOOKWAI_CONTENT_PATH . "/mk-scripts/mk-template.min.js"),
        true
      );
      wp_enqueue_style('mk-template-styles');
      wp_enqueue_script('mk-template-script');
    }

    if ($mkopt_enqueue_woocommerce !== '1') {
      $woo_prefixes = array('woo-', 'wc-', 'woocommerce');

      $woo_styles = wp_styles()->registered;
      $woo_scripts = wp_scripts()->registered;
      foreach ($woo_styles as $style_handle => $style_data) {
        foreach ($woo_prefixes as $prefix) {
            if (strpos($style_handle, $prefix) === 0) {
                wp_dequeue_style($style_handle);
                break;
            }
        }
      }
      foreach ($woo_scripts as $script_handle => $script_data) {
        foreach ($woo_prefixes as $prefix) {
            if (strpos($script_handle, $prefix) === 0) {
                wp_dequeue_script($script_handle);
                break;
            }
        }
      }
    }

    // WordPress disable
    wp_dequeue_style('wp-block-post-terms');
    wp_dequeue_style('wp-block-group');
    wp_dequeue_style('wp-block-columns');
    wp_dequeue_style('wp-block-post-template');
    wp_dequeue_style('wp-components');

    // Enternal CSS
    $external_css = get_option('mkopt_import_styles');
    if ($external_css && is_array($external_css)) {
      foreach ($external_css as $index => $item) {
        wp_enqueue_style(
          "mk-custom-styles-$index",
          $item,
          null,
          false,
          'all'
        );
      }
    }

    // Enternal JS
    $external_js = get_option('mkopt_import_scripts');
    if ($external_js && is_array($external_js)) {
      foreach ($external_js as $index => $item) {
        wp_enqueue_script(
          "mk-custom-script-$index",
          $item,
          array('vendors-core'),
          null,
          true
        );
      }
    }

    // Template scripts
    global $wpdb;
    $table_name = $wpdb->prefix . 'mookwai_block_settings';
    $theme_slug = get_stylesheet();
    if (is_front_page()) {
      $page_id = $theme_slug . '//front-page';
      $checkDb = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE page_id = %s LIMIT 1", $page_id));
      if ($checkDb) {
        if (file_exists(MOOKWAI_CONTENT_PATH . "/mk-scripts/template/mk-front-page.css")) {
          wp_register_style(
            "mk-template-front-page",
            esc_url(home_url("/wp-content/uploads/mk-scripts/template/mk-front-page.css")),
            array('mk-global-styles'),
            filemtime(MOOKWAI_CONTENT_PATH . "/mk-scripts/template/mk-front-page.css"),
            'all'
          );
          wp_register_script(
            "mk-template-front-page",
            esc_url(home_url("/wp-content/uploads/mk-scripts/template/mk-front-page.js")),
            array('mk-global-script'),
            filemtime(MOOKWAI_CONTENT_PATH . "/mk-scripts/template/mk-front-page.js"),
            true
          );
          wp_enqueue_style("mk-template-front-page");
          wp_enqueue_script("mk-template-front-page");
        }
      }
      $page_on_front = get_option('page_on_front');
      if ($page_on_front) {
        global $post;
        $getPostDate = explode("-", $post->post_date);
        $year = "/{$getPostDate[0]}";
        $month = "/{$getPostDate[1]}";
        $checkDb = $wpdb->get_results("SELECT * FROM $table_name WHERE page_id = $page_on_front LIMIT 1");
        if ($checkDb) {
          if (file_exists(MOOKWAI_CONTENT_PATH . "/mk-scripts{$year}{$month}/mk-page-{$page_on_front}.css")) {
            wp_register_style(
              "mk-page-{$page_on_front}",
              esc_url(home_url("/wp-content/uploads/mk-scripts{$year}{$month}/mk-page-{$page_on_front}.css")),
              array('mk-global-styles'),
              filemtime(MOOKWAI_CONTENT_PATH . "/mk-scripts{$year}{$month}/mk-page-{$page_on_front}.css"),
              'all'
            );
            wp_register_script(
              "mk-page-{$page_on_front}",
              esc_url(home_url("/wp-content/uploads/mk-scripts{$year}{$month}/mk-page-{$page_on_front}.js")),
              array('mk-global-script'),
              filemtime(MOOKWAI_CONTENT_PATH . "/mk-scripts{$year}{$month}/mk-page-{$page_on_front}.js"),
              true
            );
            wp_enqueue_style("mk-page-{$page_on_front}");
            wp_enqueue_script("mk-page-{$page_on_front}");
          }
        }
      }
    } else if (is_singular()) {
      global $post;
      $id = $post->ID;
      $template_slug = null;
      $template_slug = get_post_meta($id, '_wp_page_template', true);
      $post_type = get_post_type($id);
      $template_id = null;
      if ($template_slug) {
        $template_id = $theme_slug . '//' . $template_slug;
      } else if ($post_type === 'post') {
        $template_slug = 'single';
        $template_id = $theme_slug . '//' . $template_slug;
      } else if ($post_type === 'page') {
        $template_slug = 'page';
        $template_id = $theme_slug . '//' . $template_slug;
      } else {
        $template_slug = 'single-' . $post_type;
        $template_id = $theme_slug . '//' . $template_slug;
      }

      if ($template_slug) {
        $checkDb = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE page_id = %s LIMIT 1", $template_id));
        if ($checkDb) {
          if (file_exists(MOOKWAI_CONTENT_PATH . "/mk-scripts/template/mk-" . $template_slug . ".css")) {
            wp_register_style(
              "mk-template-{ $template_slug }",
              esc_url(home_url("/wp-content/uploads/mk-scripts/template/mk-" . $template_slug . ".css")),
              array('mk-global-styles'),
              filemtime(MOOKWAI_CONTENT_PATH . "/mk-scripts/template/mk-" . $template_slug . ".css"),
              'all'
            );
            wp_register_script(
              "mk-template-{ $template_slug }",
              esc_url(home_url("/wp-content/uploads/mk-scripts/template/mk-" . $template_slug . ".js")),
              array('mk-global-script'),
              filemtime(MOOKWAI_CONTENT_PATH . "/mk-scripts/template/mk-" . $template_slug . ".js"),
              true
            );
            wp_enqueue_style("mk-template-{ $template_slug }");
            wp_enqueue_script("mk-template-{ $template_slug }");
          }
        }
      }

      $getPostDate = explode("-", $post->post_date);
      $year = "/{$getPostDate[0]}";
      $month = "/{$getPostDate[1]}";
      $checkDb = $wpdb->get_results("SELECT * FROM $table_name WHERE page_id = $id LIMIT 1");
      if ($checkDb) {
        if (file_exists(MOOKWAI_CONTENT_PATH . "/mk-scripts{$year}{$month}/mk-page-{$id}.css")) {
          wp_register_style(
            "mk-page-{$id}",
            esc_url(home_url("/wp-content/uploads/mk-scripts{$year}{$month}/mk-page-{$id}.css")),
            array('mk-global-styles'),
            filemtime(MOOKWAI_CONTENT_PATH . "/mk-scripts{$year}{$month}/mk-page-{$id}.css"),
            'all'
          );
          wp_register_script(
            "mk-page-{$id}",
            esc_url(home_url("/wp-content/uploads/mk-scripts{$year}{$month}/mk-page-{$id}.js")),
            array('mk-global-script'),
            filemtime(MOOKWAI_CONTENT_PATH . "/mk-scripts{$year}{$month}/mk-page-{$id}.js"),
            true
          );
          wp_enqueue_style("mk-page-{$id}");
          wp_enqueue_script("mk-page-{$id}");
        }
      }
    } else if (is_archive()) {
      $page_id = $theme_slug . '//archive';
      $checkDb = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE page_id = %s LIMIT 1", $page_id));
      if ($checkDb) {
        if (file_exists(MOOKWAI_CONTENT_PATH . "/mk-scripts/template/mk-archive.css")) {
          wp_register_style(
            "mk-template-archive",
            esc_url(home_url("/wp-content/uploads/mk-scripts/template/mk-archive.css")),
            array('mk-global-styles'),
            filemtime(MOOKWAI_CONTENT_PATH . "/mk-scripts/template/mk-archive.css"),
            'all'
          );
          wp_register_script(
            "mk-template-archive",
            esc_url(home_url("/wp-content/uploads/mk-scripts/template/mk-archive.js")),
            array('mk-global-script'),
            filemtime(MOOKWAI_CONTENT_PATH . "/mk-scripts/template/mk-archive.js"),
            true
          );
          wp_enqueue_style("mk-template-archive");
          wp_enqueue_script("mk-template-archive");
        }
      }
    } else if (is_404()) {
      $page_id = $theme_slug . '//404';
      $checkDb = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE page_id = %s LIMIT 1", $page_id));
      if ($checkDb) {
        if (file_exists(MOOKWAI_CONTENT_PATH . "/mk-scripts/template/mk-404.css")) {
          wp_register_style(
            "mk-template-404",
            esc_url(home_url("/wp-content/uploads/mk-scripts/template/mk-404.css")),
            array('mk-global-styles'),
            filemtime(MOOKWAI_CONTENT_PATH . "/mk-scripts/template/mk-404.css"),
            'all'
          );
          wp_register_script(
            "mk-template-404",
            esc_url(home_url("/wp-content/uploads/mk-scripts/template/mk-404.js")),
            array('mk-global-script'),
            filemtime(MOOKWAI_CONTENT_PATH . "/mk-scripts/template/mk-404.js"),
            true
          );
          wp_enqueue_style("mk-template-404");
          wp_enqueue_script("mk-template-404");
        }
      }
    }

    wp_localize_script('mk-global-script', 'mookwaiData', array(
      'root_url' => get_site_url(),
      'nonce' => wp_create_nonce('wp_rest')
    ));
  }


  // 注册编辑器代码
  public function register_block_editor_assets()
  {
    // Get datas
    $mkopt_enqueue_woocommerce = get_option('mkopt_enqueue_woocommerce');
    $mkopt_enqueue_default = get_option('mkopt_enqueue_default_styles', '1');

    // Reset
    wp_dequeue_style('global-styles');

    // WooCommerce Disable
    if ($mkopt_enqueue_woocommerce !== '1') {
      $woo_prefixes = array('woo-', 'wc-', 'woocommerce');
      $wowStyles = [];
      $wowScripts = [];
      $woo_styles = wp_styles()->registered;
      $woo_scripts = wp_scripts()->registered;
      foreach ($woo_styles as $style_handle => $style_data) {
        foreach ($woo_prefixes as $prefix) {
          if (strpos($style_handle, $prefix) === 0) {
            $wowStyles[] = $style_handle;
            if ($style_handle !== 'woocommerce-blocktheme') {
              wp_dequeue_style($style_handle);
              wp_deregister_style($style_handle);
            }
            break;
          }
        }
      }
      // foreach ($woo_scripts as $script_handle => $script_data) {
      //   foreach ($woo_prefixes as $prefix) {
      //     if (strpos($script_handle, $prefix) === 0) {
      //       $wowScripts[] = $script_handle;
      //       wp_dequeue_script($script_handle);
      //       break;
      //     }
      //   }
      // }
    }

    // MooKwai scripts
    wp_register_style(
      'mk-editor-control-ui-styles',
      MOOKWAI_ASSETS_PATH . '/css/mk-editor-control-ui.css',
      null,
      filemtime(get_template_directory() . '/assets/css/mk-editor-control-ui.css'),
      'all'
    );
    wp_enqueue_style('mk-editor-control-ui-styles');

    wp_register_script(
      'mookwai-blocks-script',
      get_theme_file_uri('/assets/js/mk-blocks.js'),
      ['wp-edit-post', 'wp-i18n'],
      filemtime(get_template_directory() . '/assets/js/mk-blocks.js'),
      true
    );
    wp_enqueue_script('mookwai-blocks-script');
    wp_set_script_translations('mookwai-blocks-script', 'mookwai', get_template_directory() . '/languages');

    if ($mkopt_enqueue_default === '1') {
      wp_register_style(
        'mk-editor-default-styles',
        get_theme_file_uri('/assets/css/mk-editor-default.css'),
        null,
        filemtime(get_template_directory() . '/assets/css/mk-editor-default.css'),
        'all'
      );
      wp_enqueue_style('mk-editor-default-styles');
    }

    if (file_exists(MOOKWAI_CONTENT_PATH . "/mk-scripts/mk-editor-global.min.css")) {
      wp_register_style(
        'mk-editor-global-styles',
        esc_url(home_url("/wp-content/uploads/mk-scripts/mk-editor-global.min.css")),
        null,
        filemtime(MOOKWAI_CONTENT_PATH . "/mk-scripts/mk-editor-global.min.css"),
        'all'
      );
      wp_enqueue_style('mk-editor-global-styles');
    }
    wp_dequeue_style('global-styles-css-custom-properties');

    // Enternal CSS
    $external_css = get_option('mkopt_import_styles');
    if ($external_css && is_array($external_css)) {
      foreach ($external_css as $index => $item) {
        wp_enqueue_style(
          "mk-custom-styles-$index",
          $item,
          null,
          false,
          'all'
        );
      }
    }

    // Enternal JS
    // $external_js = get_option('mkopt_import_scripts');
    // if ($external_js && is_array($external_js)) {
    //   foreach ($external_js as $index => $item) {
    //     wp_enqueue_script(
    //       "mk-custom-script-$index",
    //       $item,
    //       array('vendors-core'),
    //       null,
    //       true
    //     );
    //   }
    // }

    wp_localize_script('wp-edit-post', 'mookwaiData', array(
      'root_url' => get_site_url(),
      'nonce' => wp_create_nonce('wp_rest'),
      'theme' => get_stylesheet(),
    ));
  }

  // Script 标签添加 module 属性
  public function add_module_to_my_script($tag, $handle, $src)
  {
    if ("manifest" === $handle) {
      $tag = '<script type="application/json" src="' . esc_url($src) . '" id="' . $handle . '-js"></script>';
    }

    if ("spline" === $handle) {
      $tag = '<script type="module" src="' . esc_url($src) . '" id="' . $handle . '-js"></script>';
    }

    return $tag;
  }

  // 移除 WordPress 多余代码
  public function remove_global_css()
  {
    remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
    remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
    remove_action('wp_footer', 'wp_enqueue_global_styles', 1);
    remove_action('wp_footer', 'the_block_template_skip_link');
  }

  // 导入页面引入的外部文件
  public function mookwai_page_import_script_file()
  {
    global $wpdb;
    $table_name = $wpdb->prefix . 'mookwai_block_settings';
    if (is_singular()) {
      global $post;
      $id = $post->ID;
      $checkDb = $wpdb->get_results("SELECT * FROM $table_name WHERE page_id = $id LIMIT 1");
      if (!$checkDb) return;
      $import_file = $checkDb[0]->import_file;
      if ($import_file) {
        $import_file = preg_replace('/<!--(.|\s)*?-->/', '', $import_file);
        $import_file = preg_replace('/<!\[if(.*?)<!\[endif\]>/', '', $import_file);
        echo $import_file;
      }
    }
  }

  function mookwai_textdomain()
  {
    load_theme_textdomain('mookwai', get_template_directory() . '/languages');
  }

  public function mookwai_set_editor_translations()
  {
    wp_set_script_translations('mookwai-blocks-script', 'mookwai', get_template_directory() . '/languages');
  }
}
