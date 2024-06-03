<?php

function containerStyles($setting, $preSetting, $prePreSetting)
{
  // Initialize
  $theStyles = '';
  $foundDisplay = null;
  $foundGap = null;
  $foundFlexDirection = null;
  $foundJustifyContent = null;
  $foundAlignItems = null;
  $foundFlexWrap = null;

  // Get Datas
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'display':
        $foundDisplay = $item;
        break;
      case 'gap':
        $foundGap = $item;
        break;
      case 'flexDirection':
        $foundFlexDirection = $item;
        break;
      case 'justifyContent':
        $foundJustifyContent = $item;
        break;
      case 'alignItems':
        $foundAlignItems = $item;
        break;
      case 'flexWrap':
        $foundFlexWrap = $item;
        break;
    }
  }
  $theDisplay = getInheritValue('display', $setting, $preSetting, $prePreSetting);

  // Generate
  $theStyles .= generateCommonStyles($foundDisplay, 'display', false);
  if (isset($theDisplay['value']) && ($theDisplay['value'] === 'flex' || $theDisplay['value'] === 'inline-flex')) {
    $theStyles .= generateCommonStyles($foundFlexDirection, 'flex-direction', false);
    $theStyles .= generateCommonStyles($foundJustifyContent, 'justify-content', false);
    $theStyles .= generateCommonStyles($foundAlignItems, 'align-items', false);
    $theStyles .= generateCommonStyles($foundFlexWrap, 'flex-wrap', false);
  }
  if (isset($theDisplay['value']) && ($theDisplay['value'] === 'grid' || $theDisplay['value'] === 'inline-grid')) {
    $theStyles .= generateCommonStyles($foundJustifyContent, 'justify-content', false);
    $theStyles .= generateCommonStyles($foundAlignItems, 'align-items', false);
  }
  if (isset($theDisplay['value']) && ($theDisplay['value'] === 'flex' || $theDisplay['value'] === 'inline-flex' || $theDisplay['value'] === 'grid' || $theDisplay['value'] === 'inline-grid')) {
    $theStyles .= generateSizingStyles($foundGap, 'gap', 'row-gap', 'column-gap', null, null, false);
  }
  return $theStyles;
}
