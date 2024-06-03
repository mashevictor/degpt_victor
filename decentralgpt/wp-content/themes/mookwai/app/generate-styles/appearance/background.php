<?php

function backgroundStyles($setting, $preSetting, $prePreSetting, $globalColor, $isImportant)
{
  $important = '';
  if ($isImportant) $important = '!important';
  // Initialize
  $theStyles = '';
  $modeStyles = [];
  $getType = null;
  $foundColor = null;
  $foundImage = null;
  $foundSize = null;
  $foundSizeX = null;
  $foundSizeY = null;
  $foundPosition = null;
  $foundPositionX = null;
  $foundPositionY = null;
  $foundRepeat = null;
  $foundAttachment = null;
  // Get Datas
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'type':
        $getType = $item['custom'];
        break;
      case 'backgroundColor':
        $foundColor = $item;
        break;
      case 'backgroundImage':
        $foundImage = $item;
        break;
      case 'backgroundSize':
        $foundSize = $item;
        break;
      case 'backgroundSizeX':
        $foundSizeX = $item;
        break;
      case 'backgroundSizeY':
        $foundSizeY = $item;
        break;
      case 'backgroundPosition':
        $foundPosition = $item;
        break;
      case 'backgroundPositionX':
        $foundPositionX = $item;
        break;
      case 'backgroundPositionY':
        $foundPositionY = $item;
        break;
      case 'backgroundRepeat':
        $foundRepeat = $item;
        break;
      case 'backgroundAttachment':
        $foundAttachment = $item;
        break;
    }
  }
  $theType = getInheritValue('type', $setting, $preSetting, $prePreSetting);
  $preType = getInheritValue('type', null, $preSetting, $prePreSetting);
  $theColor = getInheritColor('backgroundColor', $setting, $preSetting, $prePreSetting, $globalColor);
  $theImage = getInheritValue('backgroundImage', $setting, $preSetting, $prePreSetting);
  $theSize = getInheritValue('backgroundSize', $setting, $preSetting, $prePreSetting);
  $theSizeX = getInheritValue('backgroundSizeX', $setting, $preSetting, $prePreSetting);
  $theSizeY = getInheritValue('backgroundSizeY', $setting, $preSetting, $prePreSetting);
  $thePosition = getInheritValue('backgroundPosition', $setting, $preSetting, $prePreSetting);
  $thePositionX = getInheritValue('backgroundPositionX', $setting, $preSetting, $prePreSetting);
  $thePositionY = getInheritValue('backgroundPositionY', $setting, $preSetting, $prePreSetting);
  $theRepeat = getInheritValue('backgroundRepeat', $setting, $preSetting, $prePreSetting);
  $theClip = getInheritValue('backgroundClip', $setting, $preSetting, $prePreSetting);

  $type_important = isset($theType['important']) && $theType['important'] ? '!important' : '';
  $color_important = isset($theColor['important']) && $theColor['important'] ? '!important' : '';
  // Generate
  if ($getType === 'none') {
    $theStyles .= "background:none" . $type_important . ";";
  } else {
    if ($preType['value'] !== 'none' && $getType === 'color' && $preSetting) {
      $theStyles .= "background-image:none" . $type_important . ";";
      if ($theColor['value']) {
        $colorValue = null;
        $colorValue = $theColor['value'];
        if ($theColor['type'] === 'solid') {
          $theStyles .= "background-color:$colorValue$color_important;";
        } else if ($theColor['type'] === 'gradient') {
          $theStyles .= "background-image:$colorValue$color_important;";
        }
      }
      if (isset($theClip['value']) && $theClip['value']) {
        $clipValue = $theClip['value'];
        $clipImportant = isset($theClip['important']) && $theClip['important'] ? '!important' : '';
        $theStyles .= "background-clip:$clipValue$clipImportant;";
      }
    } else if ($foundColor) {
      if ($theColor['value']) {
        $colorValue = null;
        $colorValue = $theColor['value'];
        if ($theColor['type'] === 'solid') {
          $theStyles .= "background-color:$colorValue$color_important;";
        } else if ($theColor['type'] === 'gradient') {
          $theStyles .= "background-image:$colorValue$color_important;";
        }
      }
      if (isset($theClip['value']) && $theClip['value']) {
        $clipValue = $theClip['value'];
        $clipImportant = isset($theClip['important']) && $theClip['important'] ? '!important' : '';
        $theStyles .= "background-clip:$clipValue$clipImportant;";
      }
    }

    // Image
    if (
      ($theType['value'] === 'image' && !$preSetting) ||
      ($theType['value'] === 'image' && $preType['value'] !== 'image')
    ) {
      // New
      if ($theImage) {
        $imageImportant = isset($theImage['important']) && $theImage['important'] ? '!important' : '';
        $mediaURL = explode('@&', $theImage['value'])[1];
        $theStyles .= "background-image:url($mediaURL)$imageImportant;";
      }
      $sizeValue = 'cover';
      $positionValue = 'center';
      $repeatValue = 'repeat';
      $sizeImportant = isset($theSize['important']) && $theSize['important'] ? '!important' : '';
      $positionImportant = isset($thePosition['important']) && $thePosition['important'] ? '!important' : '';
      $repeatImportant = isset($theRepeat['important']) && $theRepeat['important'] ? '!important' : '';
      if (isset($theSize['value']) && $theSize['value'] === 'custom') {
        $sizeX = 'auto';
        $sizeY = 'auto';
        if (isset($theSizeX['value']) && $theSizeX['value']) {
          $sizeX = explode('@&', $theSizeX['value']);
          $sizeX = implode('', $sizeX);
        }
        if (isset($theSizeY['value']) && $theSizeY['value']) {
          $sizeY = explode('@&', $theSizeY['value']);
          $sizeY = implode('', $sizeY);
        }
        if ($sizeX === $sizeY) {
          $sizeValue = $sizeX;
        } else {
          $sizeValue = "$sizeX $sizeY";
        }
      } else if (isset($theSize['value']) && $theSize['value'] && $theSize['value'] !== 'custom') {
        $sizeValue = $theSize['value'];
      }
      if (isset($thePosition['value']) && $thePosition['value'] === 'custom') {
        $positionX = 'center';
        $positionY = 'center';
        if (isset($thePositionX['value'])) {
          $positionX = explode('@&', $thePositionX['value']);
          $positionX = implode('', $positionX);
        }
        if (isset($thePositionY['value'])) {
          $positionY = explode('@&', $thePositionY['value']);
          $positionY = implode('', $positionY);
        }
        if ($positionX === $positionY) {
          $positionValue = $positionX;
        } else {
          $positionValue = "$positionX $positionY";
        }
      } else if (isset($thePosition['value']) && $thePosition['value'] && $thePosition['value'] !== 'custom') {
        $positionValue = $thePosition['value'];
      }
      if (isset($theRepeat['value']) && $theRepeat['value']) {
        $repeatValue = $theRepeat['value'] . $repeatImportant;
      }
      $theStyles .= "background-size:$sizeValue$sizeImportant;";
      $theStyles .= "background-position:$positionValue$positionImportant;";
      $theStyles .= "background-repeat:$repeatValue$important;";
      $theStyles .= generateCommonStyles($foundAttachment, 'background-attachment', $isImportant);
    } else if ($theType['value'] === 'image' && $preType['value'] === 'image') {
      // Inherit
      if ($foundImage) {
        $imageImportant = isset($theImage['important']) && $theImage['important'] ? '!important' : '';
        $mediaURL = explode('@&', $theImage['value'])[1];
        $theStyles .= "background-image:url($mediaURL)$imageImportant;";
      }
      if ($foundSize || $foundSizeX || $foundSizeY) {
        $sizeValue = 'cover';
        $sizeImportant = isset($theSize['important']) && $theSize['important'] ? '!important' : '';
        if (isset($theSize['value']) && $theSize['value'] === 'custom') {
          $sizeX = 'auto';
          $sizeY = 'auto';
          if (isset($theSizeX['value']) && $theSizeX['value']) {
            $sizeX = explode('@&', $theSizeX['value']);
            $sizeX = implode('', $sizeX);
          }
          if (isset($theSizeY['value']) && $theSizeY['value']){
            $sizeY = explode('@&', $theSizeY['value']);
            $sizeY = implode('', $sizeY);
          }
          if ($sizeX === $sizeY) {
            $sizeValue = $sizeX;
          } else {
            $sizeValue = "$sizeX $sizeY";
          }
        } else if (isset($theSize['value']) && $theSize['value'] && $theSize !== 'custom') {
          $sizeValue = $theSize['value'];
        }
        $theStyles .= "background-size:$sizeValue$sizeImportant;";
      }
      if ($foundPosition || $foundPositionX || $foundPositionY) {
        $positionValue = 'center';
        $positionImportant = isset($thePosition['important']) && $thePosition['important'] ? '!important' : '';
        if (isset($thePosition) && $thePosition['value'] === 'custom') {
          $positionX = 'center';
          $positionY = 'center';
          if (isset($thePositionX['value']) && $thePositionX['value']) {
            $positionX = explode('@&', $thePositionX['value']);
            $positionX = implode('', $positionX);
          }
          if (isset($thePositionY['value']) && $thePositionY['value']){
            $positionY = explode('@&', $thePositionY['value']);
            $positionY = implode('', $positionY);
          }
          if ($positionX === $positionY) {
            $positionValue = $positionX;
          } else {
            $positionValue = "$positionX $positionY";
          }
        } else if (isset($thePosition['value']) && $thePosition['value'] && $thePosition !== 'custom') {
          $positionValue = $thePosition['value'];
        }
        $theStyles .= "background-position:$positionValue$positionImportant;";
      }
      $theStyles .= generateCommonStyles($foundRepeat, 'background-repeat', $isImportant);
      $theStyles .= generateCommonStyles($foundAttachment, 'background-attachment', $isImportant);
    }
    $mode_colors = getModeColor('backgroundColor', $setting, $globalColor);
    if ($mode_colors) {
      foreach ($mode_colors as $mode) {
        $style = '';
        if ($mode['type'] === 'solid') {
          $style .= "background-color:" . $mode['value'] . $color_important . ";";
        } else if ($mode['type'] === 'gradient') {
          $style .= "background-image:" . $mode['value'] . $color_important . ";";
        }
        $modeStyles[] = [
          'slug' => $mode['slug'],
          'style' => $style,
        ];
      }
    }
  }
  return [
    'theStyles' => $theStyles,
    'modeStyles' => $modeStyles,
  ];
}
