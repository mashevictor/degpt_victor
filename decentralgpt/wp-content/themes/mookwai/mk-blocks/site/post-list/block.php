<?php

/**
 * MooKwai blocks
 * Site - Post list
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\MK_Blocks\Site;

use WP_Query;
use WP_Block;
use MOOKWAI\App\Traits\Singleton;

class Post_List
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
    $page_key = isset($block->context['queryId']) ? 'query-' . $block->context['queryId'] . '-page' : 'query-page';
    $page     = empty($_GET[$page_key]) ? 1 : (int) $_GET[$page_key];

    $use_global_query = (isset($block->context['query']['inherit']) && $block->context['query']['inherit']);
    if ($use_global_query) {
      global $wp_query;
      if (in_the_loop()) {
        $query = clone $wp_query;
        $query->rewind_posts();
      } else {
        $query = $wp_query;
      }
    } else {
      $query_args = build_query_vars_from_query_block($block, $page);
      $query      = new WP_Query($query_args);
    }

    if (!$query->have_posts()) {
      return '';
    }

    $wrapper_attributes = '';
    $wrapper_classes = ['mk-post-list'];
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

    $post_column = '';
    $layout_type = isset($attributes['layoutType']) ? $attributes['layoutType'] : null;
    if ($layout_type === 'column') {
      $columns = $attributes['columns'];
      if ($columns) {
        $column_array = [];
        $column_array[] = "mk-col-" . $columns['reg'];
        if ($columns['lg'] !== $columns['reg']) {
          $column_array[] = "mk-col-" . $columns['lg'] . '-lg';
        }
        if ($columns['xl'] !== $columns['lg']) {
          $column_array[] = "mk-col-" . $columns['xl'] . '-xl';
        }
        if ($columns['xxl'] !== $columns['xl']) {
          $column_array[] = "mk-col-" . $columns['xxl'] . '-xxl';
        }
        if ($columns['p'] !== $columns['md']) {
          $column_array[] = "mk-col-" . $columns['p'] . '-p';
        }
        if ($columns['md'] !== $columns['reg']) {
          $column_array[] = "mk-col-" . $columns['md'] . '-md';
        }
        if ($columns['mdp'] !== $columns['p']) {
          $column_array[] = "mk-col-" . $columns['mdp'] . '-mdp';
        }
        if ($columns['sm'] !== $columns['md']) {
          $column_array[] = "mk-col-" . $columns['sm'] . '-sm';
        }
        if ($columns['smp'] !== $columns['mdp']) {
          $column_array[] = "mk-col-" . $columns['smp'] . '-smp';
        }
        $post_column .= ' ' . esc_attr(implode(' ', $column_array));
      }
    }
    $content = '';
    while ($query->have_posts()) {
      $query->the_post();
      $block_instance = $block->parsed_block;

      $block_instance['blockName'] = 'core/null';

      $post_id = get_the_ID();
      $post_type = get_post_type();
      $filter_block_context = static function ($context) use ($post_id, $post_type) {
        $context['postType'] = $post_type;
        $context['postId']   = $post_id;
        return $context;
      };
      add_filter('render_block_context', $filter_block_context);
      $block_content = (new WP_Block($block_instance))->render(array('dynamic' => false));
      remove_filter('render_block_context', $filter_block_context);
      $itemClass = 'el-entry-item';
      $post_classes = implode(' ', get_post_class($itemClass));
      $post_thumbnail = get_post_meta($post_id, '_mk_post_thumbnail', true);
      if ($post_column) {
        $post_classes .= $post_column;
      }
      if (($post_thumbnail && count($post_thumbnail)) || get_the_post_thumbnail($post_id)) {
        $post_classes .= " has-thumbnail";
      }
      $post_class_name = get_post_meta($post_id, '_mk_post_class_name', true);
      if ($post_class_name) {
        $post_classes .= " $post_class_name";
      }
      $custom_attrs = '';
      $post_item_style = get_post_meta($post_id, '_mk_post_list_item_style', true);
      if ($post_item_style) {
        $custom_attrs = ' ' . 'style="' . $post_item_style . '"';
      }
      $isLink = $attributes['isLink'];
      if ($isLink) {
        $open_in_new = $attributes['openInNew'];
        $rel_value = null;
        if (isset($attributes['linkRel']) && $attributes['linkRel']) {
          $rel_value = $attributes['linkRel'];
        }
        $rel_attrs = '';
        if (!empty($rel_value)) {
          $rel_value = implode(' ', $rel_value);
          $rel_attrs = 'rel="' . $rel_value . '"';
        }
        $target = '';
        if ($open_in_new) {
          $target = ' target="_blank"';
        }
        $content .= sprintf(
          '<article class="%1$s"%2$s>%3$s<a class="el-entry-link" href="%4$s"%5$s%6$s></a></article>',
          esc_attr($post_classes),
          $custom_attrs,
          $block_content,
          esc_url(get_the_permalink($post_id)),
          $target,
          $rel_attrs,
        );
      } else {
        $content .= sprintf(
          '<article class="%1$s"%2$s>%3$s</article>',
          esc_attr($post_classes),
          $custom_attrs,
          $block_content,
        );
      }
    }

    wp_reset_postdata();

    $globalLayout = isset($attributes['globalLayout']) && !empty($attributes['globalLayout']) ? $attributes['globalLayout'] : '';
    $result = '';
    if ($globalLayout) {
      $result = sprintf(
        '<div %1$s><div class="el-container %2$s"><div class="el-row">%3$s</div></div></div>',
        $wrapper_attributes,
        $globalLayout,
        $content
      );
    } else {
      $result = sprintf(
        '<div %1$s>%2$s</div>',
        $wrapper_attributes,
        $content
      );
    }
    return $result;
  }

  public function block_post_item_uses_featured_image($inner_blocks)
  {
    foreach ($inner_blocks as $block) {
      if ('core/post-featured-image' === $block->name) {
        return true;
      }
      if (
        'core/cover' === $block->name &&
        !empty($block->attributes['useFeaturedImage'])
      ) {
        return true;
      }
      if ($block->inner_blocks && $this->block_post_item_uses_featured_image($block->inner_blocks)) {
        return true;
      }
    }

    return false;
  }
}
