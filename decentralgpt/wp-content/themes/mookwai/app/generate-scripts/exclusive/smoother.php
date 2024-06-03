<?php

function interactSmoother($data)
{
  $setting = $data['Desktop'];
  $devices = null;
  $smooth = null;
  $smooth_touch = null;
  $effects = null;
  $effects_elements = null;
  $ease = null;
  $custom_ease = null;
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'devices':
        $devices = transformDevice($item['custom']);
        break;
      case 'smooth':
        $smooth = $item['custom'];
        break;
      case 'smoothTouch':
        $smooth_touch = $item['custom'];
        break;
      case 'effects':
        $effects = $item['custom'];
        break;
      case 'elements':
        $effects_elements = $item['custom'];
        break;
      case 'ease':
        $ease = $item['custom'];
        break;
      case 'customEase':
        $custom_ease = $item['custom'];
        break;
    }
  }
  $generate = '';
  $optionsArray = [];
  if ($devices) $optionsArray[] = "devices:{$devices}";
  if ($smooth) $optionsArray[] = "smooth:{$smooth}";
  if ($smooth_touch) $optionsArray[] = "smoothTouch:{$smooth_touch}";
  if ($effects) {
    if (isset($effects_elements) && count($effects_elements)) {
      error_log(print_r($effects_elements, true));
      $optionsArray[] = transformEffectsElements($effects_elements);
    } else {
      $optionsArray[] = "effects:true";
    }
  }
  if ($ease) {
    if ($ease !== 'custom') {
      $optionsArray[] = "ease:'" . $ease . "'";
    } else if ($custom_ease) {
      $optionsArray[] = "customEase:'" . $custom_ease . "'";
    }
  }
  if (count($optionsArray)) {
    $generate .= ',{';
    $generate .= implode(',', $optionsArray);
    $generate .= '}';
  }
  return $generate;
}

function transformEffectsElements($value)
{
  $result = '';
  if (count($value) > 1) {
    $result = "effects:[" . "'" . implode("','", $value) . "'" . "]";
  } else {
    $result = "effects:" . "'" . $value[0] . "'";
  }

  return $result;
}
