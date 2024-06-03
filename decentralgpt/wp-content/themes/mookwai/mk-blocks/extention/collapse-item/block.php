<?php

/**
 * MooKwai blocks
 * Extention - Collapse Item
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\MK_Blocks\Extention;

use MOOKWAI\App\Traits\Singleton;

require_once MOOKWAI_PATH . '/app/utils/generate-icon-xml.php';

class Collapse_Item
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
    $init_expand = $attributes['initExpand'];
    $heading_tag_name = $attributes['headingTagName'];
    $heading = $attributes['heading'];
    $head_attrs = 'aria-expanded="false"';

    // icons
    $toggle_icon = '';
    $the_expand_icon = generateIconXml($attributes['iconExpand']);
    $the_fold_icon = generateIconXml($attributes['iconFold']);
    if ($the_fold_icon) $toggle_icon .= '<span class="icon fold-icon">' . $the_fold_icon . '</span>';
    if ($the_expand_icon) $toggle_icon .= '<span class="icon expand-icon">' . $the_expand_icon . '</span>';

    // General
    $wrapper_attributes = '';
    $wrapper_classes = ['mk-collapse-item'];
    if (isset($attributes['blockID']) && $attributes['blockID']) {
      $wrapper_classes[] = $attributes['blockID'];
    }
    if (isset($init_expand) &&  $init_expand === 'expand') {
      $wrapper_classes[] = 'expanded';
      $head_attrs = 'aria-expanded="true"';
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
    $wrapper_attributes .= esc_attr(implode(' ', $wrapper_attrs));

    return sprintf(
      '<div %1$s><div class="el-head" %2$s><%3$s>%4$s</%3$s><span class="toggle-icon">%5$s</span></div><div class="el-body" %2$s><div class="inner-wrap">%6$s</div></div></div>',
      $wrapper_attributes,
      $head_attrs,
      $heading_tag_name,
      $heading,
      $toggle_icon,
      $content,
    );
  }
}
