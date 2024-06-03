<?php

/**
 * MooKwai autoloader file
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\App\Helper;

defined('ABSPATH') || exit;

function autoloader($resource = '')
{
  $resource_path  = false;
  $namespace_root = 'MOOKWAI\\';
  $resource       = trim($resource, '\\');

  if (empty($resource) || strpos($resource, '\\') === false || strpos($resource, $namespace_root) !== 0) {
    return;
  }

  $resource = str_replace($namespace_root, '', $resource);

  $path = explode(
    '\\',
    str_replace('_', '-', strtolower($resource))
  );

  if (empty($path[0]) || empty($path[1])) {
    return;
  }

  $directory = '';
  $file_name = '';

  if ('app' === $path[0]) {

    switch ($path[1]) {
      case 'traits':
        $directory = 'traits';
        $file_name = sprintf('trait-%s', trim(strtolower($path[2])));
        break;

      case 'settings':
        $directory = 'settings';
        $file_name = sprintf('%s', trim(strtolower($path[2])));
        break;

      case 'routes':
        $directory = 'routes';
        $file_name = sprintf('%s', trim(strtolower($path[2])));
        break;

      default:
        $directory = 'base';
        $file_name = sprintf('%s', trim(strtolower($path[1])));
        break;
    }

    $resource_path = sprintf('%s/app/%s/%s.php', untrailingslashit(MOOKWAI_PATH), $directory, $file_name);
  }

  if ('mk-blocks' === $path[0]) {
    $resource_path = sprintf('%s/mk-blocks/%s/%s/block.php', untrailingslashit(MOOKWAI_PATH), trim(strtolower($path[1])), trim(strtolower($path[2])));
  }

  if ('mk-custom' === $path[0]) {
    if ('blocks' === $path[1]) {
      $resource_path = sprintf('%s/mk-development/blocks/%s/block.php', untrailingslashit(MOOKWAI_PATH), trim(strtolower($path[2])));
    } else {
      $resource_path = sprintf('%s/mk-custom/%s.php', untrailingslashit(MOOKWAI_PATH), trim(strtolower($path[1])));
    }
  }

  $is_valid_file = validate_file($resource_path);

  if (!empty($resource_path) && file_exists($resource_path) && (0 === $is_valid_file || 2 === $is_valid_file)) {
    require_once($resource_path);
  }
}

spl_autoload_register('\MOOKWAI\app\helper\autoloader');
