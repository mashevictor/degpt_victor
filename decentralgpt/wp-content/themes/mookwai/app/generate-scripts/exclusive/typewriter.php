<?php

function interactTypewriter($data)
{
  $setting = $data['Desktop'];
  $devices = null;
  $words = null;
  $duration = null;
  $space_delay = null;
  $content_delay = null;
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'devices':
        $temp = $item['custom'];
        if ($temp) {
          $devices = "['" . implode("','", $temp) . "']";
        }
        break;
      case 'words':
        $temp = $item['custom'];
        if ($temp) {
          $words = "['" . implode("','", $temp) . "']";
        }
        break;
      case 'duration':
        $duration = $item['custom'];
        break;
      case 'spaceDelay':
        $space_delay = $item['custom'];
        break;
      case 'contentDelay':
        $content_delay = $item['custom'];
        break;
    }
  }

  $generate = '';
  $optionsArray = [];
  if ($words) $optionsArray[] = "words:{$words}";
  if ($duration) $optionsArray[] = "duration:{$duration}";
  if ($space_delay) $optionsArray[] = "spaceDelay:{$space_delay}";
  if ($content_delay) $optionsArray[] = "contentDelay:{$content_delay}";
  if ($devices) $optionsArray[] = "devices:{$devices}";
  if (count($optionsArray)) {
    $generate .= ',{';
    $generate .= implode(',', $optionsArray);
    $generate .= '}';
  }
  return $generate;
}
