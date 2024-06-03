<?php

function filterStyles($setting, $propertyNames)
{
  // Initialize
  $theStyles = '';
  $foundType = null;
  $foundMethods = null;

  // Get Datas
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'type':
        $foundType = $item;
        break;
      case 'filterMethod':
        $foundMethods = $item;
        break;
    }
  }

  if ($foundType && $foundType['custom'] !== 'custom') {
    $type_value = $foundType['custom'];
    foreach ($propertyNames as $property) {
      $theStyles .= "{$property}:{$type_value};";
    }
    return $theStyles;
  }

  if ($foundMethods) {
    $methods = $foundMethods['custom'];
    if ($methods && !empty($methods)) {
      $methodStyles = [];
      foreach ($methods as $method) {
        $theColumn = array_column($setting, 'property');
        $indDeco = array_search($method, $theColumn);
        if (is_numeric($indDeco)) {
          $found_item = $setting[$indDeco];
          if ($found_item) {
            $get_value = $found_item['custom'];
            $the_value = transformFilterValue($get_value);
            if ($the_value || $the_value === 0 || $the_value === '0') {
              switch ($method) {
                case 'blur':
                  $methodStyles[] = "blur({$the_value})";
                  break;
                case 'opacity':
                  $methodStyles[] = "opacity({$the_value})";
                  break;
                case 'saturate':
                  $methodStyles[] = "saturate({$the_value})";
                  break;
                case 'brightness':
                  $methodStyles[] = "brightness({$the_value})";
                  break;
                case 'contrast':
                  $methodStyles[] = "contrast({$the_value})";
                  break;
                case 'grayscale':
                  $methodStyles[] = "grayscale({$the_value})";
                  break;
                case 'hueRotate':
                  $methodStyles[] = "hueRotate({$the_value})";
                  break;
              }
            }
          }
        }
      }
      if (!empty($methodStyles)) {
        $join_value = implode(' ', $methodStyles);
        foreach ($propertyNames as $property) {
          $theStyles .= "{$property}:{$join_value};";
        }
      }
    }
  }

  return $theStyles;
}

function transformFilterValue($value)
{
  $result = null;
  if (!$value && $value !== 0 && $value !== '0') return $result;
  $theArray = explode('@&', $value);
  if ($theArray[0] && $theArray[0] !== '0' && $theArray[0] !== 0) {
    $result = implode('', $theArray);
  } else if ($theArray[0] === '0' || $theArray[0] === 0) {
    $result = '0';
  }
  return $result;
}
