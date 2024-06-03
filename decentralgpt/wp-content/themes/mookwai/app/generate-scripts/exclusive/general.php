<?php

function interactGeneral($data)
{
  $setting = $data['Desktop'];
  $devices = null;
  $reverse = null;
  $duration = null;
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'devices':
        $value = $item['custom'];
        $devices = "[";
        if ($value) $devices .= "'" . implode("','", $value) . "'";
        $devices .= "]";
        break;
      case 'reverse':
        $reverse = $item['custom'];
        break;
      case 'duration':
        $duration = $item['custom'];
        break;
    }
  }

  $generate = '';
  $optionsArray = [];
  if ($devices) $optionsArray[] = "devices:{$devices}";
  if ($reverse) $optionsArray[] = "reverse:true";
  if ($duration) $optionsArray[] = "duration:{$duration}";
  if (count($optionsArray)) {
    $generate .= ',{';
    $generate .= implode(',', $optionsArray);
    $generate .= '}';
  }
  return $generate;
}
