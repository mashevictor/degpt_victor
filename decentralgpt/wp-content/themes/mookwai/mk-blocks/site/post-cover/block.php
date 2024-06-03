<?php

/**
 * MooKwai blocks
 * Site - Post cover
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\MK_Blocks\Site;

use MOOKWAI\App\Traits\Singleton;

class Post_Cover
{
  use Singleton;

  protected function __construct()
  {
    $this->setup_hooks();
  }

  protected function setup_hooks()
  {
    add_action('init', [$this, 'registerBlock']);
  }

  public function registerBlock()
  {
    register_block_type_from_metadata(__DIR__, array(
      'render_callback' => [$this, 'outputData']
    ));
  }

  public function outputData($attributes, $content, $block)
  {
    if (!isset($block->context['postId'])) {
      return '';
    }
    $post_ID = $block->context['postId'];

    if (!in_the_loop() && have_posts()) {
      the_post();
    }

    $is_link = isset($attributes['isLink']) && $attributes['isLink'];
    $post_thumbnail = get_post_meta($post_ID, '_mk_post_thumbnail', true);
    $size_slug = isset($attributes['sizeSlug']) ? $attributes['sizeSlug'] : 'post-thumbnail';
    $on_toggle = isset($attributes['onToggle']) ? $attributes['onToggle'] : 'none';

    $cover_main = get_the_post_thumbnail($post_ID, $size_slug);
    $cover_additional = '';
    if ($post_thumbnail && count($post_thumbnail)) {
      if ($on_toggle === 'none' || $on_toggle === 'hover') {
        $cover_main = $this->generate_media($post_thumbnail[0]);
      } else if ($on_toggle === 'auto') {
        $cover_main = '';
        foreach ($post_thumbnail as $item) {
          $cover_main .= $this->generate_media($item);
        }
      }
      if ($on_toggle === 'hover' && count($post_thumbnail) > 1) {
        foreach ($post_thumbnail as $key => $value) {
          if ($key === 0) {
            continue;
          }
          $cover_additional .= $this->generate_media($value);
        }
      }
    }
    if (!$cover_main) {
      return '';
    }
    $cover_medias = $cover_main . $cover_additional;
    // classnames
    $wrapper_classes = ['mk-entry-cover'];
    if (isset($attributes['globalSlug']) && $attributes['globalSlug']) {
      $wrapper_classes[] = $attributes['globalSlug'];
    }
    if (isset($attributes['blockID']) && $attributes['blockID']) {
      $wrapper_classes[] = $attributes['blockID'];
    }
    $custom_classes = isset($attributes['cssClass']) ? $attributes['cssClass'] : [];
    if (!empty($custom_classes)) {
      foreach ($custom_classes as $item) {
        $wrapper_classes[] = $item;
      }
    }
    $wrapper_attributes = 'class="' . esc_attr(implode(' ', $wrapper_classes)) . '"';

    // Output
    $output_html = null;
    if ($is_link) {
      $link_attrs = 'href="' . esc_attr(get_the_permalink($post_ID)) . '"';
      if (isset($attributes['openInNew']) && $attributes['openInNew']) {
        $link_attrs .= ' target="_blank"';
      }
      $post_title = get_the_title($post_ID);
      $link_attrs .= ' aria-label="' . $post_title . '"';
      $output_html = sprintf(
        '<div %1$s>%2$s<a %3$s></a></div>',
        $wrapper_attributes,
        $cover_medias,
        $link_attrs,
      );
    } else {
      $output_html = sprintf(
        '<div %1$s>%2$s</div>',
        $wrapper_attributes,
        $cover_medias,
      );
    }
    return $output_html;
  }

  public function generate_media($item)
  {
    $result = '';
    $the_source = '';
    $the_media_type = $item['mediaType'];
    $the_media_responsive = isset($item['responsive']) ? $item['responsive'] : null;
    $the_media_url = isset($item['theMedia']) ? explode('@&', $item['theMedia'])[1] : null;
    if ($the_media_url) {
      if ($the_media_type === 'image') {
        $the_media_id = isset($item['theMedia']) ? explode('@&', $item['theMedia'])[0] : null;
        $the_media_size = isset($item['mediaSize']) ? $item['mediaSize'] : null;
        $the_image = '<img src="' . wp_get_attachment_image_src($the_media_id, $the_media_size)[0] . '" />';
        if ($the_media_responsive && count($the_media_responsive)) {
          foreach ($the_media_responsive as $res_data) {
            $res_client = $res_data['client'];
            $res_ratio = $res_data['ratio'];
            $res_media_id = isset($res_data['theMedia']) ? explode('@&', $res_data['theMedia'])[0] : null;
            $res_media_size = $res_data['mediaSize'];
            $res_media_url = wp_get_attachment_image_src($res_media_id, $res_media_size)[0];
            $the_source .= $this->generate_source($res_client, $res_ratio, $res_media_url);
          }
        }
        $result .= '<div class="el-cover-item" data-media="image"><picture>' . $the_source . $the_image . '</picture></div>';
      } else if ($the_media_type === 'video') {
        $the_video = '<source src="' . $the_media_url . '" />';
        $result .= '<div class="el-cover-item" data-media="video"><video playsinline autoplay loop muted>' . $the_source . $the_video . '</video></div>';
      }
    }
    return $result;
  }

  public function generate_source($client, $ratio, $media_url)
  {
    if (!$media_url) return '';
    $result = '';
    if ($ratio === 'landscape') {
      if ($client === 'Large') {
        $result .= '<source media="(min-width: 1601px) and (orientation: landscape)" srcset="' . $media_url . '">';
      } else if ($client === 'Tablet') {
        $result .= '<source media="(max-width: 1200px) and (orientation: landscape)" srcset="' . $media_url . '">';
      } else if ($client === 'Mobile') {
        $result .= '<source media="(max-width: 900px) and (orientation: landscape)" srcset="' . $media_url . '">';
      } else {
        $result .= '<source media="(min-width: 1201px) and (orientation: landscape)" srcset="' . $media_url . '">';
      }
    } else if ($ratio === 'portrait') {
      if ($client === 'Large') {
        $result .= '<source media="(min-width: 1201px) and (orientation: portrait)" srcset="' . $media_url . '">';
      } else if ($client === 'Tablet') {
        $result .= '<source media="(max-width: 900px) and (orientation: portrait)" srcset="' . $media_url . '">';
      } else if ($client === 'Mobile') {
        $result .= '<source media="(max-width: 600px) and (orientation: portrait)" srcset="' . $media_url . '">';
      } else {
        $result .= '<source media="(min-width: 901px) and (orientation: portrait)" srcset="' . $media_url . '">';
      }
    } else {
      if ($client === 'Large') {
        $result .= '<source media="(min-width: 1601px)" srcset="' . $media_url . '">';
      } else if ($client === 'Tablet') {
        $result .= '<source media="(max-width: 1200px)" srcset="' . $media_url . '">';
      } else if ($client === 'Mobile') {
        $result .= '<source media="(max-width: 900px)" srcset="' . $media_url . '">';
      } else {
        $result .= '<source media="(min-width: 1201px)" srcset="' . $media_url . '">';
      }
    }
    return $result;
  }
}
