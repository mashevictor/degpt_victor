<?php

function interactMasonry($data)
{
  $setting = $data['Desktop'];
  $devices = null;
  $minWidth = null;
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'devices':
        $value = $item['custom'];
        $devices = "[";
        if ($value) $devices .= "'" . implode("','", $value) . "'";
        $devices .= "]";
        break;
      case 'minWidth':
        $minWidth = $item['custom'];
        break;
    }
  }

  $generate = '';
  $optionsArray = [];
  if ($devices) $optionsArray[] = "devices:{$devices}";
  if ($minWidth) $optionsArray[] = "minWidth:{$minWidth}";
  if (count($optionsArray)) {
    $generate .= ',{';
    $generate .= implode(',', $optionsArray);
    $generate .= '}';
  }
  return $generate;
}
