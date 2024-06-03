<?php

/**
 * MooKwai blocks
 * Site - Post excerpt
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\MK_Blocks\Site;

use MOOKWAI\App\Traits\Singleton;

class Post_Excerpt
{
  use Singleton;

  protected function __construct()
  {
    $this->setup_hooks();
    if (
      is_admin() ||
      defined('REST_REQUEST') && REST_REQUEST
    ) {
      add_filter(
        'excerpt_length',
        static function () {
          return 100;
        },
        PHP_INT_MAX
      );
    }
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

    $tag_name = $attributes['tagName'];
    $excerpt_length = $attributes['excerptLength'];
    $excerpt = get_the_excerpt($block->context['postId']);
    if (isset($excerpt_length)) {
      $excerpt = wp_trim_words($excerpt, $excerpt_length);
    }

    // $more_text = !empty($attributes['moreText']) ? '<a class="wp-block-post-excerpt__more-link" href="' . esc_url(get_the_permalink($block->context['postId'])) . '">' . wp_kses_post($attributes['moreText']) . '</a>' : '';
    // $filter_excerpt_more = static function ($more) use ($more_text) {
    //   return empty($more_text) ? $more : '';
    // };
    // add_filter('excerpt_more', $filter_excerpt_more);
    $wrapper_attributes = '';
    $wrapper_classes = ['mk-entry-excerpt'];
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
    $wrapper_attributes .= 'class="' . esc_attr(implode(' ', $wrapper_classes)) . '"';
    $custom_attrs = isset($attributes['htmlAttr']) ? $attributes['htmlAttr'] : [];
    $wrapper_attrs = [];
    if (!empty($custom_attrs)) {
      foreach ($custom_attrs as $item) {
        foreach ($item as $key => $value) {
          $wrapper_attrs[] = $key . '=' . $value;
        }
      }
    }
    $wrapper_attributes .= ' ' . esc_attr(implode(' ', $wrapper_attrs));

    // remove_filter('excerpt_more', $filter_excerpt_more);
    return sprintf(
      '<%1$s %2$s>%3$s</%1$s>',
      $tag_name,
      $wrapper_attributes,
      $excerpt
    );
  }
}
