<?php

function GlobalStylesColor($preSelector, $datas)
{
  $theStyles = '';
  if ($preSelector) return $theStyles;
  $brand = $datas['brand'];
  $neutral = $datas['neutral'];
  $hint = $datas['hint'];
  $other = $datas['other'];
  foreach ($brand as $color) {
    $slug = $color['slug'];
    $type = $color['type'];
    $value = $color['solidValue'];
    if ($type === 'gradient') {
      $value = $color['gradientValue'];
    }
    $theStyles .= "--$slug:$value;";
  }
  foreach ($neutral as $color) {
    $slug = $color['slug'];
    $type = $color['type'];
    $value = $color['solidValue'];
    if ($type === 'gradient') {
      $value = $color['gradientValue'];
    }
    $theStyles .= "--$slug:$value;";
  }
  foreach ($hint as $color) {
    $slug = $color['slug'];
    $type = $color['type'];
    $value = $color['solidValue'];
    if ($type === 'gradient') {
      $value = $color['gradientValue'];
    }
    $theStyles .= "--$slug:$value;";
  }
  foreach ($other as $color) {
    $slug = $color['slug'];
    $type = $color['type'];
    $value = $color['solidValue'];
    if ($type === 'gradient') {
      $value = $color['gradientValue'];
    }
    $theStyles .= "--$slug:$value;";
  }
  return $theStyles;
}
