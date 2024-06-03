<?php

/**
 * MooKwai frontend head
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\App;

defined('ABSPATH') || exit;

use MOOKWAI\App\Traits\Singleton;

class MooKwai_Head
{

  use Singleton;

  protected function __construct()
  {
    $this->setup_hooks();
  }

  protected function setup_hooks()
  {
    add_action('wp_head', [$this, 'mookwai_theme_color'], 100);
    add_action('wp_head', [$this, 'mookwai_theme_favicon'], 100);
    add_action('admin_head', [$this, 'mookwai_theme_favicon'], 100);
    add_action('wp_head', [$this, 'mookwai_allow_apple_mobile_app'], 100);
    add_action('wp_head', [$this, 'mookwai_custom_head_tag'], 100);
    add_action('wp_head', [$this, 'mookwai_analitics'], 100);
    add_action('wp_body_open', [$this, 'mookwai_google_analitics_body'], 100);
  }

  public static function mookwai_theme_color()
  {
    if (get_option('mkopt_theme_color')) {
      echo '<meta name="theme-color" content="' . get_option('mkopt_theme_color') . '">';
    }
  }

  public static function mookwai_theme_favicon()
  {
    if (get_option('mkopt_favicon') || get_option('mkopt_favicon_l')) {
      remove_action('wp_head', 'wp_site_icon', 99);
      $favicon_id = get_option('mkopt_favicon');
      $favicon_large_id = get_option('mkopt_favicon_l');
      $mookwai_favicon = null;
      $mookwai_favicon_medium = null;
      $mookwai_favicon_large = null;
      if ($favicon_id) {
        $favicon_data = wp_get_attachment_metadata( $favicon_id );
        if ( $favicon_data && isset( $favicon_data['sizes'] ) && is_array($favicon_data['sizes']) && count($favicon_data['sizes']) ) {
          $min_size_name = null;
          $med_size_name = null;
          $big_size_name = null;

          $min_size_width = PHP_INT_MAX;
          $min_size_height = PHP_INT_MAX;
          $medium_diff = PHP_INT_MAX;
          $large_diff = PHP_INT_MAX;
          foreach ( $favicon_data['sizes'] as $size_name => $size_data ) {
            if ( $size_data['width'] < $min_size_width || $size_data['height'] < $min_size_height ) {
                $min_size_name = $size_name;
                $min_size_width = $size_data['width'];
                $min_size_height = $size_data['height'];
            }
            $ratio_size = $size_data['width'];
            if ($size_data['width'] > $size_data['height']) {
              $ratio_size = $size_data['height'];
            }
            if (abs( $ratio_size - 300 ) < $medium_diff) {
              $med_size_name = $size_name;
              $medium_diff = abs( $ratio_size - 300 );
            }
            if (abs( $ratio_size - 600 ) < $large_diff) {
              $big_size_name = $size_name;
              $large_diff = abs( $ratio_size - 600 );
            }
          }
          if ( $min_size_name ) {
            $mookwai_favicon = wp_get_attachment_image_src( $favicon_id, $min_size_name )[0];
          }
          if ( $med_size_name ) {
            $mookwai_favicon_medium = wp_get_attachment_image_src( $favicon_id, $med_size_name )[0];
          }
          if ( $big_size_name ) {
            $mookwai_favicon_large = wp_get_attachment_image_src( $favicon_id, $big_size_name )[0];
          }
        } else {
          $mookwai_favicon = wp_get_attachment_image_src( $favicon_id )[0];
          $mookwai_favicon_medium = $mookwai_favicon;
          $mookwai_favicon_large = $mookwai_favicon;
        }
      }
      if($favicon_large_id) {
        $favicon_data = wp_get_attachment_metadata( $favicon_large_id );
        if ( $favicon_data && isset( $favicon_data['sizes'] ) && is_array($favicon_data['sizes']) && count($favicon_data['sizes']) ) {
          $min_size_name = null;
          $med_size_name = null;
          $big_size_name = null;

          $min_size_width = PHP_INT_MAX;
          $min_size_height = PHP_INT_MAX;
          $medium_diff = PHP_INT_MAX;
          $large_diff = PHP_INT_MAX;
          foreach ( $favicon_data['sizes'] as $size_name => $size_data ) {
            if ( $size_data['width'] < $min_size_width || $size_data['height'] < $min_size_height ) {
                $min_size_name = $size_name;
                $min_size_width = $size_data['width'];
                $min_size_height = $size_data['height'];
            }
            $ratio_size = $size_data['width'];
            if ($size_data['width'] > $size_data['height']) {
              $ratio_size = $size_data['height'];
            }
            if (abs( $ratio_size - 300 ) < $medium_diff) {
              $med_size_name = $size_name;
              $medium_diff = abs( $ratio_size - 300 );
            }
            if (abs( $ratio_size - 600 ) < $large_diff) {
              $big_size_name = $size_name;
              $large_diff = abs( $ratio_size - 600 );
            }
          }
          if ( $min_size_name && ! $mookwai_favicon ) {
            $mookwai_favicon = wp_get_attachment_image_src( $favicon_large_id, $min_size_name )[0];
          }
          if ( $med_size_name && ! $mookwai_favicon_medium ) {
            $mookwai_favicon_medium = wp_get_attachment_image_src( $favicon_large_id, $med_size_name )[0];
          }
          if ( $big_size_name ) {
            $mookwai_favicon_large = wp_get_attachment_image_src( $favicon_large_id, $big_size_name )[0];
          }
        } else {
          $mookwai_favicon_large = wp_get_attachment_image_src( $favicon_id )[0];
          if (! $mookwai_favicon) {
            $mookwai_favicon = $mookwai_favicon_large;
          }
          if (! $mookwai_favicon_medium) {
            $mookwai_favicon_medium = $mookwai_favicon_large;
          }
        }
      }
      echo '<link rel="icon" href="' . $mookwai_favicon . '" sizes="32x32"><link rel="icon" href="' . $mookwai_favicon_medium . '" sizes="192x192"><link rel="apple-touch-icon" href="' . $mookwai_favicon_large . '">';
    }
  }

  public static function mookwai_allow_apple_mobile_app()
  {
    $ios_mobile_app_option = get_option('mkopt_ios_mobile_app');
    if (empty($ios_mobile_app_option)) return;
    echo '<meta name="apple-mobile-web-app-capable" content="yes">';
  }

  public static function mookwai_custom_head_tag() {
    $mkopt_custom_head = get_option('mkopt_custom_head');
    if (empty($mkopt_custom_head)) return;
    echo $mkopt_custom_head;
  }

  public static function mookwai_analitics()
  {
    if (get_option('mkopt_analytics_baidu_src')) {
    ?>
      <script>
        var _hmt = _hmt || [];
        (function() {
          var hm = document.createElement("script");
          hm.src = "<?php echo get_option('mkopt_analytics_baidu_src') ?>";
          var s = document.getElementsByTagName("script")[0];
          s.parentNode.insertBefore(hm, s);
        })();
      </script>
    <?php
    }
    if (get_option('mkopt_analytics_google_uid')) {
    ?>
      <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo get_option('mkopt_analytics_google_uid') ?>"></script>
      <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
          dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', '<?php echo get_option('mkopt_analytics_google_uid') ?>');
      </script>
    <?php }
    if (get_option('mkopt_analytics_google_gtm')) {
    ?>
      <script>
        (function(w, d, s, l, i) {
          w[l] = w[l] || [];
          w[l].push({
            'gtm.start': new Date().getTime(),
            event: 'gtm.js'
          });
          var f = d.getElementsByTagName(s)[0],
            j = d.createElement(s),
            dl = l != 'dataLayer' ? '&l=' + l : '';
          j.async = true;
          j.src =
            'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
          f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', '<?php echo get_option('mkopt_analytics_google_gtm') ?>');
      </script>
    <?php
    }
  }

  public static function mookwai_google_analitics_body()
  {
    if (!get_option('mkopt_analytics_google_gtm')) return;
    print '<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=' . get_option('mkopt_analytics_google_gtm') . '"
      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>';
  }

}