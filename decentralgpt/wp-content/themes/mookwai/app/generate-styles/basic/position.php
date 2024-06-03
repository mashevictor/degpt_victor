<?php

function positionStyles($setting, $preSetting, $prePreSetting)
{
  // Initialize
  $theStyles = '';
  $foundPosition = null;
  $foundInset = null;
  $foundZIndex = null;

  // Get Datas
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'position':
        $foundPosition = $item;
        break;
      case 'inset':
        $foundInset = $item;
        break;
      case 'zIndex':
        $foundZIndex = $item;
        break;
    }
  }
  $thePosition = getInheritValue('position', $setting, $preSetting, $prePreSetting);

  // Generate
  $theStyles .= generateCommonStyles($foundPosition, 'position', false);
  if (isset($thePosition['value']) && $thePosition['value'] && $thePosition['value'] !== 'static') {
    $theStyles .= generateSizingStyles(
      $foundInset,
      'inset',
      'top',
      'right',
      'bottom',
      'left',
      false
    );
    $theStyles .= generateCommonStyles($foundZIndex, 'z-index', false);
  }
  return $theStyles;
}
