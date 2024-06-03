<?php

function spacingStyles($setting)
{
  // Initialize
  $theStyles = '';
  $foundPadding = null;
  $foundMargin = null;

  // Get Datas
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'padding':
        $foundPadding = $item;
        break;
      case 'margin':
        $foundMargin = $item;
        break;
    }
  }

  // Generate
  $theStyles .= generateSizingStyles($foundPadding, 'padding', 'padding-top', 'padding-right', 'padding-bottom', 'padding-left', false);
  $theStyles .= generateSizingStyles($foundMargin, 'margin', 'margin-top', 'margin-right', 'margin-bottom', 'margin-left', false);

  return $theStyles;
}
