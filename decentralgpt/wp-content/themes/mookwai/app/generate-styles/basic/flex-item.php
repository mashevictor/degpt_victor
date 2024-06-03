<?php

function flexItemStyles($setting)
{
  // Initialize
  $theStyles = '';
  $foundJustifySelf = null;
  $foundAlignSelf = null;
  $foundFlexGrow = null;
  $foundFlexShrink = null;
  $foundFlexBasis = null;
  $foundOrder = null;

  // Get Datas
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'justifySelf':
        $foundJustifySelf = $item;
        break;
      case 'alignSelf':
        $foundAlignSelf = $item;
        break;
      case 'flexGrow':
        $foundFlexGrow = $item;
        break;
      case 'flexShrink':
        $foundFlexShrink = $item;
        break;
      case 'flexBasis':
        $foundFlexBasis = $item;
        break;
      case 'order':
        $foundOrder = $item;
        break;
    }
  }

  // Generate
  $theStyles .= generateCommonStyles($foundJustifySelf, 'justify-self', false);
  $theStyles .= generateCommonStyles($foundAlignSelf, 'align-self', false);
  $theStyles .= generateCommonStyles($foundFlexGrow, 'flex-grow', false);
  $theStyles .= generateCommonStyles($foundFlexShrink, 'flex-shrink', false);
  $theStyles .= generateSizingStyles($foundFlexBasis, 'flex-basis', null, null, null, null, false);
  $theStyles .= generateCommonStyles($foundOrder, 'order', false);

  return $theStyles;
}
