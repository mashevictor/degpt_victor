<?php

/**
 * MooKwai reset
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\App;

defined('ABSPATH') || exit;

use MOOKWAI\App\Traits\Singleton;

class MooKwai_Reset
{

  use Singleton;

  protected function __construct()
  {
    $this->setup_hooks();
  }

  protected function setup_hooks()
  {
    add_action('init', [$this, 'mookwai_reset_default']);
    add_filter('body_class', [$this, 'mookwai_body_classes']);
    add_filter('admin_body_class', [$this, 'mookwai_admin_classes']);
    add_filter('excerpt_more', [$this, 'custom_excerpt_more']);
    add_filter('upload_mimes', [$this, 'add_woff_format_upload_mimes'], 1, 1);
    add_filter('render_block', [$this, 'mookwai_template_part_classes'], 10, 2);
    add_action('wp_footer', [$this, 'mk_add_overlay_slot_container']);
    add_filter('excerpt_length', [$this, 'mk_custom_excerpt_length'], 999);
    add_filter( 'block_editor_settings_all', [$this, 'mookwai_block_editor_settings'], 1000000000, 2 );
    add_filter( 'should_load_remote_block_patterns', '__return_false' );
    add_action( 'init', [$this, 'mookwai_remove_block_patterns'], 100 );
    add_filter('wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {
      $filetype = wp_check_filetype($filename, $mimes);
      return [
        'ext'             => $filetype['ext'],
        'type'            => $filetype['type'],
        'proper_filename' => $data['proper_filename']
      ];
    }, 10, 4);
  }

  function mookwai_remove_block_patterns() {
    $registered_patterns = \WP_Block_Patterns_Registry::get_instance()->get_all_registered();
    if ( $registered_patterns ) {
      foreach ( $registered_patterns as $pattern_properties ) {
        unregister_block_pattern( $pattern_properties['name'] );
      }
    }
    remove_theme_support( 'core-block-patterns' );
  }

  function mookwai_block_editor_settings( $settings ) {
    $settings['supportsLayout'] = false;
    $settings['disableLayoutStyles'] = true;
    $settings['defaultEditorStyles'] = [];
    $settings['styles'] = [];
    $settings['__experimentalAdditionalBlockPatterns'] = [];
    $settings['__experimentalFeatures'] = [];
    $settings['colors'] = [];
    $settings['gradients'] = [];
    $settings['fontSizes'] = [];
    $settings['spacingSizes'] = [];
    return $settings;
  }

  function mk_custom_excerpt_length()
  {
    return 300;
  }

  public function mookwai_reset_default()
  {
    // 隐藏顶部管理员工具栏
    if (!get_option('mkopt_show_admin_bar', '1')) {
      show_admin_bar(false);
    }

    // 移除头部加载 DNS 预获取 (dns-prefetch)
    remove_action('wp_head', 'wp_resource_hints', 2);

    // 移除head中emoji表情script
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');

    // 移除头部 wp-json 标签和 HTTP header 中的 link
    remove_action('wp_head', 'rest_output_link_wp_head', 10);
    remove_action('template_redirect', 'rest_output_link_header', 11);

    // 移除远程发布元素
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');

    // 移除 WordPress 版本信息
    remove_action('wp_head', 'wp_generator');
  }

  public function mookwai_body_classes(array $classes): array
  {
    $classes[] = 'mookwai';
    if (is_front_page() || is_home()) {
      if (is_front_page()) {
        $classes[] = 'mk-template-front-page';
      } else if (is_home()) {
        $classes[] = 'mk-template-home';
      }
      $page_on_front = get_option('page_on_front');
      if ($page_on_front) {
        global $post;
        $post_id = $post->ID;
        $custom_field_value = get_post_meta($post_id, '_mk_post_class_name', true);
        if ($custom_field_value) {
          $classes[] = $custom_field_value;
        }
      }
    } else if (is_singular()) {
      global $post;
      $post_id = $post->ID;
      $custom_field_value = get_post_meta($post_id, '_mk_post_class_name', true);
      if ($custom_field_value) {
        $classes[] = $custom_field_value;
      }
    }
    $get_the_mode = $this->mk_get_page_mode();
    echo $get_the_mode;
    return $classes;
  }

  public function mookwai_admin_classes($classes)
  {
    global $pagenow;
    if (in_array($pagenow, array('post.php', 'post-new.php', 'site-editor.php', 'admin.php'), true)) {
      $classes .= ' mookwai';
    }
    return $classes;
  }

  public function custom_excerpt_more($more)
  {
    return '…';
  }

  public static function add_woff_format_upload_mimes($existing_mimes)
  {
    $existing_mimes['woff'] = 'application/x-font-woff';
    $existing_mimes['woff2'] = 'application/x-font-woff2';
    $existing_mimes['json'] = 'text/plain';
    return $existing_mimes;
  }

  public function mookwai_template_part_classes($block_content, $block)
  {
    if ($block['blockName'] !== 'core/template-part') {
      return $block_content;
    }
    $the_content = preg_replace('/wp-block-template-part/i', 'mk-template-part', $block_content);
    return $this->removeTags($the_content, array("template"));
  }

  public function removeTags($htmlString, $htmlTags)
  {
    $tagString = "";
    foreach ($htmlTags as $key => $value) {
      $tagString .= $key == count($htmlTags) - 1 ? $value : "{$value}|";
    }
    $pattern = array("/(<\s*\b({$tagString})\b[^>]*>)/i", "/(<\/\s*\b({$tagString})\b\s*>)/i");
    $result = preg_replace($pattern, "", $htmlString);
    return $result;
  }

  public function mk_add_overlay_slot_container()
  {
    echo '<div class="mk-overlay-mask"></div><div class="mk-overlay-slot"></div>';
  }

  public function mk_get_page_mode()
  {
    global $wpdb;
    $mk_setting_table = $wpdb->prefix . 'mookwai_block_settings';
    $theme_prefix = get_stylesheet();
    $result = 'mode-defaut';
    $pageId = null;
    $color_scheme = null;
    if (is_singular()) {
      global $post;
      $pageId = $post->ID;
      $post_type = $post->post_type;
      $post_name = $post->post_name;
      $template_slug = get_page_template_slug($pageId);
      $get_data = $wpdb->prepare("SELECT color_scheme from $mk_setting_table WHERE page_id = %s", array($pageId));
      $get_data = $wpdb->get_results($get_data);
      if ($get_data) {
        $color_scheme = $get_data[0]->color_scheme;
      }
      if (is_page()) {
        if ($color_scheme) {
          $result = $color_scheme;
        } else if ($template_slug) {
          $pageId = $theme_prefix . "//" . $template_slug;
          $get_data = $wpdb->prepare("SELECT color_scheme from $mk_setting_table WHERE page_id = %s", array($pageId));
          $get_data = $wpdb->get_results($get_data);
          if ($get_data) {
            $color_scheme = $get_data[0]->color_scheme;
          }
          if ($color_scheme) {
            $result = $color_scheme;
          }
        } else {
          $pageId = $theme_prefix . "//page-" . $post_name;
          $get_data = $wpdb->prepare("SELECT color_scheme from $mk_setting_table WHERE page_id = %s", array($pageId));
          $get_data = $wpdb->get_results($get_data);
          if ($get_data) {
            $color_scheme = $get_data[0]->color_scheme;
          }
          if ($color_scheme) {
            $result = $color_scheme;
          } else {
            $pageId = $theme_prefix . "//page";
            $get_data = $wpdb->prepare("SELECT color_scheme from $mk_setting_table WHERE page_id = %s", array($pageId));
            $get_data = $wpdb->get_results($get_data);
            if ($get_data) {
              $color_scheme = $get_data[0]->color_scheme;
            }
            if ($color_scheme) {
              $result = $color_scheme;
            } else {
              $pageId = $theme_prefix . "//singular";
              $get_data = $wpdb->prepare("SELECT color_scheme from $mk_setting_table WHERE page_id = %s", array($pageId));
              $get_data = $wpdb->get_results($get_data);
              if ($get_data) {
                $color_scheme = $get_data[0]->color_scheme;
              }
              if ($color_scheme) {
                $result = $color_scheme;
              }
            }
          }
        }
      } else {
        if ($color_scheme) {
          $result = $color_scheme;
        } else if ($template_slug) {
          $pageId = $theme_prefix . "//" . $template_slug;
          $get_data = $wpdb->prepare("SELECT color_scheme from $mk_setting_table WHERE page_id = %s", array($pageId));
          $get_data = $wpdb->get_results($get_data);
          if ($get_data) {
            $color_scheme = $get_data[0]->color_scheme;
          }
          if ($color_scheme) {
            $result = $color_scheme;
          }
        } else {
          $pageId = $theme_prefix . "//single-" . $post_type . '-' . $post_name;
          $get_data = $wpdb->prepare("SELECT color_scheme from $mk_setting_table WHERE page_id = %s", array($pageId));
          $get_data = $wpdb->get_results($get_data);
          if ($get_data) {
            $color_scheme = $get_data[0]->color_scheme;
          }
          if ($color_scheme) {
            $result = $color_scheme;
          } else {
            $pageId = $theme_prefix . "//single-" . $post_type;
            $get_data = $wpdb->prepare("SELECT color_scheme from $mk_setting_table WHERE page_id = %s", array($pageId));
            $get_data = $wpdb->get_results($get_data);
            if ($get_data) {
              $color_scheme = $get_data[0]->color_scheme;
            }
            if ($color_scheme) {
              $result = $color_scheme;
            } else {
              $pageId = $theme_prefix . "//single";
              $get_data = $wpdb->prepare("SELECT color_scheme from $mk_setting_table WHERE page_id = %s", array($pageId));
              $get_data = $wpdb->get_results($get_data);
              if ($get_data) {
                $color_scheme = $get_data[0]->color_scheme;
              }
              if ($color_scheme) {
                $result = $color_scheme;
              } else {
                $pageId = $theme_prefix . "//singular";
                $get_data = $wpdb->prepare("SELECT color_scheme from $mk_setting_table WHERE page_id = %s", array($pageId));
                $get_data = $wpdb->get_results($get_data);
                if ($get_data) {
                  $color_scheme = $get_data[0]->color_scheme;
                }
                if ($color_scheme) {
                  $result = $color_scheme;
                }
              }
            }
          }
        }
      }
    } else {
      if (is_front_page() || is_home()) {
        if (is_front_page()) {
          $pageId = $theme_prefix . "//front-page";
        } else {
          $pageId = $theme_prefix . "//home";
        }
        $get_data = $wpdb->prepare("SELECT color_scheme from $mk_setting_table WHERE page_id = %s", array($pageId));
        $get_data = $wpdb->get_results($get_data);
        if ($get_data) {
          $color_scheme = $get_data[0]->color_scheme;
        }
        if ($color_scheme) {
          $result = $color_scheme;
        }
      }
      if (is_archive()) {
        $result = 'archive';
      }
      if (is_search()) {
        $result = 'search';
      }
      if (is_404()) {
        $result = '404';
      }
    }
    $result = "data-mode='$result' ";
    $mkopt_custom_body_attributes = get_option('mkopt_custom_body_attributes');
    if ($mkopt_custom_body_attributes) {
      $result .= "$mkopt_custom_body_attributes ";
    }
    return $result;
  }
}
