<?php

function overflowStyles($setting)
{
  // Initialize
  $theStyles = '';
  $foundOverflowX = null;
  $foundOverflowY = null;
  $foundTextOverflow = null;

  // Get Datas
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'overflowX':
        $foundOverflowX = $item;
        break;
      case 'overflowY':
        $foundOverflowY = $item;
        break;
      case 'textOverflow':
        $foundTextOverflow = $item;
        break;
    }
  }

  // Generate
  if ($foundOverflowX && $foundOverflowY && $foundOverflowX['custom'] === $foundOverflowY['custom']) {
    $theStyles .= generateCommonStyles($foundOverflowX, 'overflow', false);
  } else {
    $theStyles .= generateCommonStyles($foundOverflowX, 'overflow-x', false);
    $theStyles .= generateCommonStyles($foundOverflowY, 'overflow-y', false);
  }
  $theStyles .= generateCommonStyles($foundTextOverflow, 'text-overflow', false);

  return $theStyles;
}
