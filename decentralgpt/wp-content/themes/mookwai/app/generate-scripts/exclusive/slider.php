<?php

function interactSlider($data)
{
  $setting = $data['Desktop'];
  $devices = "['Desktop', 'Tablet', 'Mobile']";
  $pause_on_hover = null;
  $stay = null;
  $duration = null;
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'devices':
        $temp = $item['custom'];
        if ($temp) {
          $devices = "['" . implode("','", $temp) . "']";
        }
        break;
      case 'pauseOnHover':
        $pause_on_hover = $item['custom'];
        break;
      case 'stay':
        $stay = $item['custom'];
        break;
      case 'duration':
        $duration = $item['custom'];
        break;
    }
  }

  $generate = '';
  $optionsArray = [];
  if ($devices) $optionsArray[] = "devices:{$devices}";
  if ($pause_on_hover) $optionsArray[] = "pauseOnHover:true";
  if ($stay) $optionsArray[] = "stay:{$stay}";
  if ($duration) $optionsArray[] = "duration:{$duration}";
  if (count($optionsArray)) {
    $generate .= ',{';
    $generate .= implode(',', $optionsArray);
    $generate .= '}';
  }
  return $generate;
}

function interactSliderControls($data)
{
  $setting = $data['Desktop'];
  $devices = null;
  $auto_hide = null;
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'devices':
        $temp = $item['custom'];
        if ($temp) {
          $devices = "['" . implode("','", $temp) . "']";
        }
        break;
      case 'autoHide':
        $auto_hide = $item['custom'];
        break;
    }
  }
  if (!$auto_hide) return;
  $generate = '';
  $optionsArray = [];
  if ($devices) $optionsArray[] = "devices:{$devices}";
  if ($auto_hide) $optionsArray[] = "autoHide:true";
  if (count($optionsArray)) {
    $generate .= ',{';
    $generate .= implode(',', $optionsArray);
    $generate .= '}';
  }
  return $generate;
}
