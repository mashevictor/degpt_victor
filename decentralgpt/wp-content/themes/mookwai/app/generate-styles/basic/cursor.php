<?php

function cursorStyles($setting)
{
  // Initialize
  $theStyles = '';
  $foundCursor = null;
  $foundCustom = null;
  $foundPointerEvents = null;

  // Get Datas
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'cursor':
        $foundCursor = $item;
        break;
      case 'customCursor':
        $foundCustom = $item;
        break;
      case 'pointerEvents':
        $foundPointerEvents = $item;
        break;
    }
  }
  // Generate
  if($foundCursor) {
    $cursor_value = $foundCursor['custom'];
    $cursor_important = $foundCursor['important'];
    if ($foundCustom && $foundCustom['custom'] && $cursor_value === 'custom') {
      $cursor_important = $foundCursor['important'] || $foundCustom['important'];
      $cursor_value = $foundCustom['custom'];

    }
    $important = '';
    if($cursor_important) {
      $important = '!important';
    }
    $theStyles .= 'cursor:' . $cursor_value . $important . ';';
  }
  // $theStyles .= generateCommonStyles($foundCursor, 'cursor', false);
  $theStyles .= generateCommonStyles($foundPointerEvents, 'pointer-events', false);

  return $theStyles;
}
