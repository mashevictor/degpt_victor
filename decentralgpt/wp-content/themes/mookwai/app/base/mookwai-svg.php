<?php

/**
 * MooKwai SVG support
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\App;

defined('ABSPATH') || exit;

use MOOKWAI\App\Traits\Singleton;

class MooKwai_Svg
{

  use Singleton;

  protected function __construct()
  {

    $this->setup_hooks();
  }

  protected function setup_hooks()
  {
    add_action('after_setup_theme', [$this, 'setup_theme']);
    add_filter('upload_mimes', [$this, 'cc_mime_types']);
    add_action('admin_head', [$this, 'fix_svg']);
  }

  public static function setup_theme()
  {
    add_filter('wp_check_filetype_and_ext', function ($data, $file, $filename, $mimes) {

      global $wp_version;
      if ($wp_version !== '4.7.1') {
        return $data;
      }

      $filetype = wp_check_filetype($filename, $mimes);

      return [
        'ext'             => $filetype['ext'],
        'type'            => $filetype['type'],
        'proper_filename' => $data['proper_filename']
      ];
    }, 10, 4);
  }

  public static function cc_mime_types($mimes)
  {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
  }

  public static function fix_svg()
  {
    echo '<style type="text/css">
          .attachment-266x266, .thumbnail img {
               width: 100% !important;
               height: auto !important;
          }
          </style>';
  }
}
