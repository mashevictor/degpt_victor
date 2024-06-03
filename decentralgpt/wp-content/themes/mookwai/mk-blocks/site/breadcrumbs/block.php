<?php

/**
 * MooKwai blocks
 * Site - Breadcrumbs
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\MK_Blocks\Site;

use MOOKWAI\App\Traits\Singleton;

class Breadcrumbs
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
    $post_id = $block->context['postId'];
    $post  = get_post($post_id);

    // Global
    $current_name = $attributes['currentName'];

    // Separator
    $separator = $attributes['separator'];
    $separator_attr = 'class="el-separator"';
    if ($separator === 'text') {
      $separator_text = $attributes['separatorText'];
      $separator = sprintf(
        '<span %1$s>%2$s</span>',
        $separator_attr,
        $separator_text
      );
    } else if ($separator === 'icon') {
      $the_icon = generateIconXml($attributes['separatorIcon']);
      $separator = sprintf(
        '<span %1$s>%2$s</span>',
        $separator_attr,
        $the_icon
      );
    }

    // Prefix
    $prefix = null;
    if (isset($attributes['prefix'])) {
      $prefix = $attributes['prefix'];
    }
    $content_prefix = '';
    if ($prefix) {
      $content_prefix = sprintf(
        '<span class="el-item">%1$s</span>%2$s',
        $prefix,
        $separator
      );
    }

    // Terms
    $the_term = $attributes['term'];
    $post_terms = wp_get_post_terms($post_id, $the_term);
    $find_demersal = array_filter($post_terms, function ($v) {
      return $v->parent !== 0;
    });
    if (count($find_demersal)) {
      $spare = [];
      foreach ($find_demersal as $item) {
        $spare[] = $item->parent;
      }
      $new_demersal = [];
      foreach ($find_demersal as $item) {
        if (!in_array($item->term_taxonomy_id, $spare)) {
          $new_demersal[] = $item;
        }
      }
      $find_demersal = $new_demersal[0]->term_taxonomy_id;
    } else if (count($post_terms)) {
      $find_demersal = $post_terms[0]->term_taxonomy_id;
    } else {
      $find_demersal = null;
    }

    $all_terms = get_terms($the_term, array(
      'hide_empty' => 0,
    ));
    $get_terms_list = [];
    if (count($all_terms) && $find_demersal) {
      $get_terms_list = $this->get_list_item($all_terms, $find_demersal);
    }

    $content_terms = '';
    if (count($get_terms_list)) {
      $is_link = $attributes['isLink'];
      $is_blank = $attributes['openInNew'];
      $term_tag = $is_link ? 'a' : 'span';
      foreach ($get_terms_list as $i => $item) {
        $term_attributes = [];
        $term_attributes[] = 'class="el-item"';
        if ($is_link) {
          $the_link = get_term_link($item->term_id, $the_term);
          $term_attributes[] = 'href="' . $the_link . '"';
          if ($is_blank) $term_attributes[] = 'target="_blank"';
        }
        $term_attributes = implode(' ', $term_attributes);
        if (count($get_terms_list) - 1 > $i || $current_name) {
          $content_terms .= sprintf(
            '<%1$s %2$s>%3$s</%1$s>%4$s',
            $term_tag,
            $term_attributes,
            $item->name,
            $separator
          );
        } else {
          $content_terms .= sprintf(
            '<%1$s %2$s>%3$s</%1$s>',
            $term_tag,
            $term_attributes,
            $item->name
          );
        }
      }
    }

    // Title name
    $title = get_the_title($post);
    $content_title = '';
    if ($current_name) {
      $content_title = sprintf(
        '<span class="el-item current">%1$s</span>',
        $title
      );
    }

    // General
    $wrapper_attributes = '';
    $wrapper_classes = ['mk-breadcrumb'];
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
    if (isset($attributes['anchor']) && $attributes['anchor']) {
      $wrapper_attrs[] = 'id=' . $attributes['anchor'];
    }
    if (!empty($custom_attrs)) {
      foreach ($custom_attrs as $item) {
        foreach ($item as $key => $value) {
          $wrapper_attrs[] = $key . '=' . $value;
        }
      }
    }
    $wrapper_attributes .= ' ' . esc_attr(implode(' ', $wrapper_attrs));

    return sprintf(
      '<div %1$s>%2$s%3$s%4$s</div>',
      $wrapper_attributes,
      $content_prefix,
      $content_terms,
      $content_title
    );
  }

  protected function get_list_item($items_list, $item_id)
  {
    $get_terms_list = [];
    $theColumn = array_column($items_list, 'term_taxonomy_id');
    $indDeco = array_search($item_id, $theColumn);
    $found = $items_list[$indDeco];
    array_unshift($get_terms_list, $found);
    if ($found->parent) {
      $parent = $this->get_list_item($items_list, $found->parent);
      array_unshift($get_terms_list, ...$parent);
    }
    return $get_terms_list;
  }
}
