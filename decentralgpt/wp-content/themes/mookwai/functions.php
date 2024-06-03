<?php

/**
 * MooKwai functions and definitions
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */
if (!defined('ABSPATH')) exit;

if (!defined('MOOKWAI_PATH')) {
  define('MOOKWAI_PATH', untrailingslashit(get_template_directory()));
}

if (!defined('MOOKWAI_ASSETS_PATH')) {
  define('MOOKWAI_ASSETS_PATH', untrailingslashit(get_template_directory_uri()) . '/assets');
}

if (!defined('MOOKWAI_DLL_PATH')) {
  define('MOOKWAI_DLL_PATH', untrailingslashit(get_template_directory_uri()) . '/dll');
}

if (!defined('MOOKWAI_CONTENT_PATH')) {
  define('MOOKWAI_CONTENT_PATH', ABSPATH . 'wp-content/uploads');
}

include(MOOKWAI_PATH . '/app/helper/autoloader.php');

MOOKWAI\App\MooKwai_Setup::get_instance();
