<?php

/**
 * MooKwai blocks
 * Basic - Icon
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\MK_Blocks\Basic;

use MOOKWAI\App\Traits\Singleton;

require_once MOOKWAI_PATH . '/app/utils/generate-icon-xml.php';

class Icon
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
    $tag_name = $attributes['tagName'];
    $the_icon = generateIconXml($attributes['icon']);

    // General
    $wrapper_attributes = '';
    $wrapper_classes = ['mk-icon'];
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

    $link_attrs = '';
    if (isset($attributes['linkEnable']) && isset($attributes['link']) && isset($attributes['link']['theLink']) && $attributes['linkEnable'] && $attributes['link']['theLink']) {
      $rel_array = [];
      $link_attrs .= ' href="' . $attributes['link']['theLink'] . '"';
      if (isset($attributes['openInNew']) && $attributes['openInNew']) {
        $link_attrs .= ' target="_blank"';
        $rel_array[] = 'noopener';
      }
      if (isset($attributes['ariaLabel']) && $attributes['ariaLabel']) {
        $link_attrs .= ' aria-label="' . $attributes['ariaLabel'] . '"';
      }
      if (isset($attributes['linkRel']) && $attributes['linkRel']) {
        $temp = $attributes['linkRel'];
        if (isset($attributes['openInNew']) && $attributes['openInNew']) {
          $temp = array_filter($temp, function ($item) {
            return $item !== 'noopener';
          });
          $rel_array = array_merge($rel_array, $temp);
        }
      }
      if (count($rel_array)) {
        $link_attrs .= ' rel="' . esc_attr(implode(' ', $rel_array)) . '"';
      }
    }
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
    $wrapper_attributes .= $link_attrs . ' ' . esc_attr(implode(' ', $wrapper_attrs));

    return sprintf(
      '<%1$s %2$s>%3$s</%1$s>',
      $tag_name,
      $wrapper_attributes,
      $the_icon
    );
  }
}
