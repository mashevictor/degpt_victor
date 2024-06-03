<?php

function opacityStyles($setting)
{
  // Initialize
  $theStyles = '';
  $foundOpacity = null;
  $foundMixBlendMode = null;

  // Get Datas
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'opacity':
        $foundOpacity = $item;
        break;
      case 'mixBlendMode':
        $foundMixBlendMode = $item;
        break;
    }
  }

  // Generate
  $theStyles .= generateCommonStyles($foundOpacity, 'opacity', false);
  $theStyles .= generateCommonStyles($foundMixBlendMode, 'mix-blend-mode', false);

  return $theStyles;
}
