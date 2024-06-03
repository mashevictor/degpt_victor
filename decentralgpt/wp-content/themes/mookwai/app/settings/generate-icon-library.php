<?php

/**
 * MooKwai generate mookwai icon library
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

$MooKwaiSource = file_get_contents(MOOKWAI_PATH . "/icon-library/source.json");
$json_MooKwaiSource = json_decode($MooKwaiSource);

$MooKwaiIconContent = '';


$the_cats = $json_MooKwaiSource->category;
$libraryDatas = (object)[];
$smallArray = (object)[];
$mediumArray = (object)[];
$largeArray = (object)[];
foreach ($the_cats as $cat) {
  $theSlug = $cat->slug;
  $theName = $cat->name;
  $smallArray->$theSlug = (object)['name' => $theName, 'icons' => []];
  $mediumArray->$theSlug = (object)['name' => $theName, 'icons' => []];
  $largeArray->$theSlug = (object)['name' => $theName, 'icons' => []];
}
$the_icons = $json_MooKwaiSource->icons;
foreach ($the_icons as $icon) {
  $icon_slug = $icon->slug;
  $icon_name = $icon->name;
  $icon_cats = $icon->category;
  $icon_small = isset($icon->small) ? $icon->small : null;
  $icon_medium = isset($icon->medium) ? $icon->medium : null;
  $icon_large = isset($icon->large) ? $icon->large : null;

  if ($icon_small) {
    foreach ($icon_cats as $cat) {
      $newItem = (object)['slug' => $icon_slug, 'name' => $icon_name, 'xml' => $icon_small];
      array_push($smallArray->$cat->icons, $newItem);
    }
  }
  if ($icon_medium) {
    foreach ($icon_cats as $cat) {
      $newItem = (object)['slug' => $icon_slug, 'name' => $icon_name, 'xml' => $icon_medium];
      array_push($mediumArray->$cat->icons, $newItem);
    }
  }
  if ($icon_large) {
    foreach ($icon_cats as $cat) {
      $newItem = (object)['slug' => $icon_slug, 'name' => $icon_name, 'xml' => $icon_large];
      array_push($largeArray->$cat->icons, $newItem);
    }
  }
}
$libraryDatas->small = $smallArray;
$libraryDatas->medium = $mediumArray;
$libraryDatas->large = $largeArray;

foreach ($libraryDatas as $size => $sizeValue) {
  foreach ($sizeValue as $cat => $catValue) {
    if (!count($catValue->icons)) {
      unset($libraryDatas->$size->$cat);
    }
  }
}

foreach ($libraryDatas as $size => $sizeValue) {
  if (!(array)$sizeValue) {
    unset($libraryDatas->$size);
  }
}

$generateLibrary = ["slug" => "MooKwaiIcon", "name" => "MooKwai Icon", "data" => $libraryDatas];

$MooKwaiIconContent = json_encode($generateLibrary);
