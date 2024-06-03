<?php

function transformStyles($setting, $firstSetting, $secondSetting, $thirdSetting)
{
  // Initialize
  $theStyles = '';
  $foundTransform = null;
  $foundOrigin = null;
  $foundMethods = null;

  // Get Datas
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'transform':
        $foundTransform = $item;
        break;
      case 'transformOrigin':
        $foundOrigin = $item;
        break;
      case 'transformMethod':
        $foundMethods = $item;
        break;
    }
  }
  if (!$foundMethods) {
    foreach ($firstSetting as $item) {
      switch ($item['property']) {
        case 'transformMethod':
          $foundMethods = $item;
          break;
      }
    }
  }
  if (!$foundMethods) {
    foreach ($secondSetting as $item) {
      switch ($item['property']) {
        case 'transformMethod':
          $foundMethods = $item;
          break;
      }
    }
  }
  if (!$foundMethods) {
    foreach ($thirdSetting as $item) {
      switch ($item['property']) {
        case 'transformMethod':
          $foundMethods = $item;
          break;
      }
    }
  }

  if ($foundTransform && $foundTransform['custom'] !== 'custom') {
    $transform_value = $foundTransform['custom'];
    return "transform:{$transform_value};";
  }

  if ($foundOrigin) {
    $the_value = $foundOrigin['custom'];
    $the_x = transformValue($the_value['X']);
    $the_y = transformValue($the_value['Y']);
    if ($the_x && $the_y) {
      $theStyles .= "transform-origin:{$the_x} {$the_y};";
    } else if ($the_x && !$the_y) {
      $theStyles .= "transform-origin:{$the_x} 0;";
    } else if (!$the_x && $the_y) {
      $theStyles .= "transform-origin:0 {$the_y};";
    }
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
          $the_value = null;
          if ($found_item) $the_value = $found_item['custom'];
          if ($the_value) {
            $the_x = isset($the_value['X']) ? transformValue($the_value['X']) : null;
            $the_y = isset($the_value['Y']) ? transformValue($the_value['Y']) : null;
            $the_z = isset($the_value['Z']) ? transformValue($the_value['Z']) : null;
            switch ($method) {
              case 'translate':
                if ($the_x !== null && $the_y !== null && $the_z !== null) {
                  $methodStyles[] = "translate3d({$the_x},{$the_y},{$the_z})";
                } else if ($the_x !== null && $the_y !== null && $the_z === null) {
                  $methodStyles[] = "translate({$the_x},{$the_y})";
                } else {
                  if ($the_x !== null) $methodStyles[] = "translateX({$the_x})";
                  if ($the_y !== null) $methodStyles[] = "translateY({$the_y})";
                  if ($the_z !== null) $methodStyles[] = "translateZ({$the_z})";
                }
                break;
              case 'scale':
                if ($the_x && $the_y && $the_z) {
                  $methodStyles[] = "scale3d({$the_x},{$the_y},{$the_z})";
                } else if ($the_x && $the_y && !$the_z) {
                  if ($the_x === $the_y) {
                    $methodStyles[] = "scale({$the_x})";
                  } else {
                    $methodStyles[] = "scale({$the_x},{$the_y})";
                  }
                } else {
                  if ($the_x !== null) $methodStyles[] = "scaleX({$the_x})";
                  if ($the_y !== null) $methodStyles[] = "scaleY({$the_y})";
                  if ($the_z !== null) $methodStyles[] = "scaleZ({$the_z})";
                }
                break;
              case 'rotate':
                if ($the_z) $methodStyles[] = "rotate({$the_z})";
                break;
              case 'skew':
                if ($the_x && $the_y) {
                  $methodStyles[] = "skew({$the_x},{$the_y})";
                } else {
                  if ($the_x !== null) $methodStyles[] = "skew({$the_x})";
                  if ($the_y !== null) $methodStyles[] = "skewY({$the_y})";
                }
                break;
            }
          }
        }
      }
      if (!empty($methodStyles)) {
        $join_value = implode(' ', $methodStyles);
        $theStyles .= "transform:{$join_value};";
      }
    }
  }

  return $theStyles;
}

function transformValue($value)
{
  $result = null;
  if (!$value) return $result;
  $theArray = explode('@&', $value);
  if ($theArray[0] && $theArray[0] !== '0') {
    $result = implode('', $theArray);
  } else if ($theArray[0] === '0' || $theArray[0] === 0) {
    $result = '0';
  }
  return $result;
}
