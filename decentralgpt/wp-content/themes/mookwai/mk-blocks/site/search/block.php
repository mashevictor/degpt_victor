<?php

/**
 * MooKwai blocks
 * Site - Search
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\MK_Blocks\Site;

use MOOKWAI\App\Traits\Singleton;

class Search
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
    // General
    $wrapper_attributes = '';
    $wrapper_classes = ['mk-search'];
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

    // Search
    $is_icon = $attributes['isIcon'];
    $is_button = $attributes['isButton'];
    $the_search_icon = '';
    $the_placeholder = $attributes['placeholder'] ? ' placeholder="' . $attributes['placeholder'] . '"' : '';
    $input_attrs = '';
    if (!$attributes['autoComplete']) {
      $input_attrs .= ' autocomplete="off"';
    }
    $the_search_input = sprintf(
      '<input type="search" class="search-input"%1$s name="s"%2$s/>',
      $the_placeholder,
      $input_attrs
    );
    $the_search_button = '';
    if ($is_icon) {
      $the_icon = generateIconXml($attributes['searchIcon']);
      $the_search_icon = sprintf(
        '<span class="search-icon">%1$s</span>',
        $the_icon
      );
    }
    if ($is_button) {
      $the_content = '';
      $the_text = $attributes['buttonText'];
      $the_icon = generateIconXml($attributes['buttonIcon']);
      if ($attributes['buttonType'] === 'both') {
        $the_content = '<span class="icon">' . $the_icon . '</span>';
        $the_content .= $the_text;
      } else if ($attributes['buttonType'] === 'icon') {
        $the_content = '<span class="icon">' . $the_icon . '</span>';
      } else {
        $the_content = $the_text;
      }
      $the_search_button = sprintf(
        '<button type="submit" class="search-button">%1$s</button>',
        $the_content
      );
    }
    return sprintf(
      '<form role="search" method="get" %1$s action="%2$s"><div class="el-wrapper">%3$s%4$s%5$s</div></form>',
      $wrapper_attributes,
      esc_url(home_url('/')),
      $the_search_icon,
      $the_search_input,
      $the_search_button
    );
  }
}
