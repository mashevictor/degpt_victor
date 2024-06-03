<?php

/**
 * MooKwai register metas
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\App;

defined('ABSPATH') || exit;

use MOOKWAI\App\Traits\Singleton;

class MooKwai_Metas
{

  use Singleton;

  protected function __construct()
  {
    $this->setup_hooks();
  }

  protected function setup_hooks()
  {
    add_action('init', [$this, 'mookwai_editor_width_resister_options']);
    add_action('init', [$this, 'global_register_meta']);
    add_action('init', [$this, 'popup_post_type_register_meta']);
  }

  public function mookwai_editor_width_resister_options()
  {
    register_setting(
      'mkopt_editor_width_options',
      'mkopt_editor_width_default',
      [
        'default'       => '',
        'show_in_rest'  => true,
        'type'          => 'string',
      ]
    );
  }

  public function global_register_meta()
  {
    register_meta('post', '_mk_post_edit_width', array(
      'single' => true,
      'type' => 'string',
      'default' => 'default',
      'show_in_rest' => true,
      'sanitize_callback' => 'sanitize_text_field',
      'auth_callback' => function () {
        return current_user_can('edit_posts');
      }
    ));

    register_meta('post', '_mk_post_class_name', array(
      'single' => true,
      'type' => 'string',
      'default' => '',
      'show_in_rest' => true,
      'sanitize_callback' => 'sanitize_text_field',
      'auth_callback' => function () {
        return current_user_can('edit_posts');
      }
    ));

    register_meta('post', '_mk_post_list_item_style', array(
      'single' => true,
      'type' => 'string',
      'default' => '',
      'show_in_rest' => true,
      'sanitize_callback' => 'sanitize_text_field',
      'auth_callback' => function () {
        return current_user_can('edit_posts');
      }
    ));

    register_meta('post', '_mk_post_thumbnail', array(
      'single' => true,
      'type' => 'array',
      'show_in_rest' => array(
        'schema' => array(
          'items' => array(
            'type' => 'object',
            'properties' => array(
              'mediaType' => array(
                'type' => 'string',
              ),
              'theMedia' => array(
                'type' => 'string',
              ),
              'mediaSize' => array(
                'type' => 'string',
              ),
              'responsive' => array(
                'type' => 'array',
                'items' => array(
                  'type' => 'object',
                  'properties' => array(
                    'client' => array(
                      'type' => 'string'
                    ),
                    'ratio' => array(
                      'type' => 'string'
                    ),
                    'theMedia' => array(
                      'type' => 'string'
                    ),
                    'mediaSize' => array(
                      'type' => 'string'
                    )
                  )
                )
              )
            ),
          ),
        ),
      ),
      'auth_callback' => function () {
        return current_user_can('edit_posts');
      }
    ));
  }

  public function popup_post_type_register_meta()
  {
    register_meta('post', '_mk_popup_disable_page_scroll', array(
      'object_subtype' => 'popup',
      'single' => true,
      'type' => 'boolean',
      'default' => true,
      'show_in_rest' => true,
      'sanitize_callback' => 'sanitize_text_field',
      'auth_callback' => function () {
        return current_user_can('edit_posts');
      }
    ));
    register_meta('post', '_mk_popup_show_on_page_load', array(
      'object_subtype' => 'popup',
      'single' => true,
      'type' => 'boolean',
      'default' => false,
      'show_in_rest' => true,
      'sanitize_callback' => 'sanitize_text_field',
      'auth_callback' => function () {
        return current_user_can('edit_posts');
      }
    ));
  }
}
