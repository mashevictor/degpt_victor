<?php

/**
 * MooKwai generate library icon to xml
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

function generateIconXml($data)
{
  if (!isset($data)) return;
  $icon_slug = $data['slug'];
  $icon_size = $data['size'];
  $icon_lib = $data['library'];
  $the_library = null;
  if ($icon_lib === 'MooKwaiIcon') {
    $mookwai_icon_path = MOOKWAI_PATH . '/assets/icon-library/source.json';
    $mookwai_library = json_decode(file_get_contents($mookwai_icon_path));
    $the_library = $mookwai_library;
  } else {
    $custom_icon_path = MOOKWAI_CONTENT_PATH . '/mk-scripts/icon-library.json';
    $custom_library = json_decode(file_get_contents($custom_icon_path));
    $foundLibrary = array_search($icon_lib, array_column($custom_library, 'slug'));
    $the_library = $custom_library[$foundLibrary];
  }
  $the_icons = $the_library->icons;
  $theColumn = array_column($the_icons, 'slug');
  $found = array_search($icon_slug, $theColumn);
  if (!$found && $found !== 0) return;
  $result = $the_icons[$found]->$icon_size;
  return $result;
}
