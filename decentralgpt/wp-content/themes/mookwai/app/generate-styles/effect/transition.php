<?php

function transitionStyles($setting, $preSetting, $prePreSetting)
{
  // Initialize
  $theStyles = '';
  $found = null;

  // Get Datas
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'transition':
        $found = $item;
        break;
    }
  }

  $valueArray = isset($found['custom']) ? $found['custom'] : null;
  if (!$valueArray) return $theStyles;

  // Generate
  $theResult = [];
  foreach ($valueArray as $item) {
    $theColumn = array_column($setting, 'property');
    $indFound = array_search($item, $theColumn);
    if (is_numeric($indFound)) {
      $found_item = $setting[$indFound];
      $the_value = null;
      if ($found_item) $the_value = $found_item['custom'];
      if ($the_value) {
        $property = isset($the_value['property']) ? $the_value['property'] : '';
        $duration = isset($the_value['duration']) ? $the_value['duration'] . 's' : '';
        $delay = isset($the_value['delay']) ? $the_value['delay'] . 's' : '';
        $timingFunction = isset($the_value['ease']) ? $the_value['ease'] : '';
        if ($duration) {
          $the_content = [];
          if ($property) $the_content[] = $property;
          $the_content[] = $duration;
          if ($timingFunction) $the_content[] = $timingFunction;
          if ($delay) $the_content[] = $delay;
          $the_content = implode(' ', $the_content);
          $theResult[] = $the_content;
        }
      }
    }
  }

  if (count($theResult)) {
    $theResult = implode(',', $theResult);
    $theStyles .= "transition:{$theResult};";
  }
  return $theStyles;
}
