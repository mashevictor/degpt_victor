<?php

/**
 * MooKwai blocks
 * Site - Post terms
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\MK_Blocks\Site;

use MOOKWAI\App\Traits\Singleton;

class Post_Terms
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
      'render_callback' => [$this, 'outputData'],
    ));
  }

  public function outputData($attributes, $content, $block)
  {
    if (!isset($block->context['postId']) || !isset($attributes['term'])) {
      return '';
    }

    if (!is_taxonomy_viewable($attributes['term'])) {
      return '';
    }

    $post_terms = get_the_terms($block->context['postId'], $attributes['term']);
    if (is_wp_error($post_terms) || empty($post_terms)) {
      return '';
    }

    // General
    $wrapper_attributes = '';
    $wrapper_classes = array('mk-entry-terms', 'taxonomy-' . $attributes['term']);
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
    if (!empty($wrapper_attrs)) {
      $wrapper_attributes .= ' ' . esc_attr(implode(' ', $wrapper_attrs));
    }

    $separator = '<span class="el-separator">, </span>';
    if (isset($attributes['separator']) && $attributes['separator']) {
      $separator = '<span class="el-separator">' . $attributes['separator'] . '</span>';
    }

    $prefix = '';
    if (isset($attributes['prefix']) && $attributes['prefix']) {
      $prefix .= '<span class="el-prefix">' . $attributes['prefix'] . '</span>';
    }

    $suffix = '';
    if (isset($attributes['suffix']) && $attributes['suffix']) {
      $suffix .= '<span class="el-suffix">' . $attributes['suffix'] . '</span>' . $suffix;
    }

    $is_link = $attributes['isLink'];
    $terms = get_the_terms($block->context['postId'], $attributes['term']);
    $term_item = [];
    if (!empty($terms)) {
      foreach ($terms as $term) {
        if ($is_link) {
          $term_item[] = sprintf(
            '<a href="%1$s" title="%2$s" class="el-taxonomy-item">%2$s</a>',
            esc_url(get_term_link($term->slug, $attributes['term'])),
            esc_html($term->name)
          );
        } else {
          $term_item[] = sprintf(
            '<span class="el-taxonomy-item">%1$s</span>',
            esc_html($term->name)
          );
        }
      }
    }

    return sprintf(
      '<div %1$s>%3$s%2$s%4$s</div>',
      $wrapper_attributes,
      implode($separator, $term_item),
      $prefix,
      $suffix,
    );
  }
}
