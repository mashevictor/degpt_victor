<?php

function sizeStyles($setting)
{
  // Initialize
  $theStyles = '';
  $foundWidth = null;
  $foundMinWidth = null;
  $foundMaxWidth = null;
  $foundHeight = null;
  $foundMinHeight = null;
  $foundMaxHeight = null;
  $foundRatio = null;

  // Get Datas
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'width':
        $foundWidth = $item;
        break;
      case 'minWidth':
        $foundMinWidth = $item;
        break;
      case 'maxWidth':
        $foundMaxWidth = $item;
        break;
      case 'height':
        $foundHeight = $item;
        break;
      case 'minHeight':
        $foundMinHeight = $item;
        break;
      case 'maxHeight':
        $foundMaxHeight = $item;
        break;
      case 'ratio':
        $foundRatio = $item;
        break;
    }
  }

  // Generate
  $theStyles .= generateSizingStyles($foundWidth, 'width', null, null, null, null, false);
  $theStyles .= generateSizingStyles($foundMinWidth, 'min-width', null, null, null, null, false);
  $theStyles .= generateSizingStyles($foundMaxWidth, 'max-width', null, null, null, null, false);
  $theStyles .= generateSizingStyles($foundHeight, 'height', null, null, null, null, false);
  $theStyles .= generateSizingStyles($foundMinHeight, 'min-height', null, null, null, null, false);
  $theStyles .= generateSizingStyles($foundMaxHeight, 'max-height', null, null, null, null, false);
  $theStyles .= generateCommonStyles($foundRatio, 'aspect-ratio', false);

  return $theStyles;
}
