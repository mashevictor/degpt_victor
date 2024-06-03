<?php

function borderRadiusStyles($setting)
{
  // Initialize
  $theStyles = '';
  $foundRadius = null;

  // Get Datas
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'borderRadius':
        $foundRadius = $item;
        break;
    }
  }

  // Generate
  $theStyles .= generateSizingStyles(
    $foundRadius,
    'border-radius',
    'border-top-left-radius',
    'border-top-right-radius',
    'border-bottom-right-radius',
    'border-bottom-left-radius',
    false
  );

  return $theStyles;
}
