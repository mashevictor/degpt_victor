<?php

function clipStyles($setting)
{
  // Initialize
  $theStyles = '';
  $foundType = null;
  $foundClip = null;

  // Get Datas
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'type':
        $foundType = $item;
        break;
      case 'clipPath':
        $foundClip = $item;
        break;
    }
  }
  if (isset($foundType['custom']) && ($foundType['custom'] === 'inherit' || $foundType['custom'] === 'initial' || $foundType['custom'] === 'none' || $foundType['custom'] === 'revert' || $foundType['custom'] === 'revert-layer' || $foundType['custom'] === 'unset')) {
    $theStyles .= generateCommonStyles($foundType, 'clip-path', false);
    return $theStyles;
  }
  $theStyles .= generateCommonStyles($foundClip, 'clip-path', false);

  return $theStyles;
}
