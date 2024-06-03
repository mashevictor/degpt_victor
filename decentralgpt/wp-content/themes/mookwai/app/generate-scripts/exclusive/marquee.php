<?php

function interactMarquee($data)
{
  $setting = $data['Desktop'];
  $devices = "['Desktop', 'Tablet', 'Mobile']";
  $speed = null;
  $scrollCatch = null;
  $reverse = null;
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'devices':
        $temp = $item['custom'];
        if ($temp) {
          $devices = "['" . implode("','", $temp) . "']";
        }
        break;
      case 'speed':
        $speed = $item['custom'];
        break;
      case 'scrollCatch':
        $scrollCatch = $item['custom'];
        break;
      case 'reverse':
        $reverse = $item['custom'];
        break;
    }
  }

  $generate = '';
  $optionsArray = [];
  if ($devices) $optionsArray[] = "devices:{$devices}";
  if ($speed) $optionsArray[] = "speed:{$speed}";
  if ($scrollCatch) $optionsArray[] = "scrollCatch:true";
  if ($reverse) $optionsArray[] = "reverse:true";
  if (count($optionsArray)) {
    $generate .= ',{';
    $generate .= implode(',', $optionsArray);
    $generate .= '}';
  }
  return $generate;
}
