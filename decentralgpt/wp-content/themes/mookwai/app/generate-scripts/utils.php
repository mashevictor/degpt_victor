<?php

function transformValueSize($value)
{
  if (!$value) return 0;
  $result = explode('@&', $value);
  if (!$result[0] || $result[0] === '0') return 0;
  $result = implode('', $result);
  return $result;
}

function transformDevice($value)
{
  $result = "[";
  if ($value && count($value)) $result .= "'" . implode("','", $value) . "'";
  $result .= "]";
  return $result;
}

function transformAnimations($animations, $activeAnimations, $is_page)
{
  if (!$animations || !count($animations)) return null;
  $theArray = [];
  foreach ($animations as $animation) {
    $is_active = $animation['animationID'];
    if (in_array($is_active, $activeAnimations)) {
      $the_animation = transformAnimation($animation, $is_page);
      if ($the_animation) $theArray[] = $the_animation;
    }
  }
  $generate = '';
  if (count($theArray)) {
    $generate .= '[';
    $generate .= implode(',', $theArray);
    $generate .= ']';
  }
  return $generate;
}

function transformAnimation($animation, $is_page)
{
  if (!isset($animation['clips']) || !count($animation['clips'])) return null;
  $theArray = [];
  if (isset($animation['animationID'])) {
    $theArray[] = "animationID:'" . $animation['animationID'] . "'";
  }
  if (isset($animation['repeat'])) {
    $theArray[] = "repeat:" . $animation['repeat'];
  }
  if (isset($animation['maxTime'])) {
    $theArray[] = "maxTime:" . $animation['maxTime'];
  }
  $the_clips = transformClips($animation['clips'], $is_page);
  if ($the_clips) $theArray[] = $the_clips;
  $generate = '';
  if (count($theArray)) {
    $generate .= '{';
    $generate .= implode(',', $theArray);
    $generate .= '}';
  }
  return $generate;
}

function transformClips($clips, $is_page)
{
  $theArray = [];
  foreach ($clips as $clip) {
    switch ($clip['clipType']) {
      case 'element':
        $the_clip = transformClipElement($clip, $is_page, 'element');
        if ($the_clip) $theArray[] = $the_clip;
        break;
      case 'text':
        $the_clip = transformClipElement($clip, $is_page, 'text');
        if ($the_clip) $theArray[] = $the_clip;
        break;
      case 'lottie':
        $the_clip = transformClipLottie($clip);
        if ($the_clip) $theArray[] = $the_clip;
        break;
      case 'video':
        $the_clip = transformClipVideo($clip);
        if ($the_clip) $theArray[] = $the_clip;
        break;
    }
  }
  $generate = '';
  if (count($theArray)) {
    $generate .= 'clips:[';
    $generate .= implode(',', $theArray);
    $generate .= ']';
  }
  return $generate;
}

function transformClipElement($value, $is_page, $type)
{
  $theArray = [];
  $the_method = 'to';
  $theArray[] = "clipType:'" . $type . "'";
  if (isset($value['target'])) {
    $get_target = $value['target'];
    if (!count($get_target) && $is_page) return;
    if (count($get_target)) {
      $the_target = "[";
      $the_target .= "'" . implode("','", $get_target) . "'";
      $the_target .= "]";
      $theArray[] = "target:" . $the_target;
    }
  }
  if (isset($value['child'])) {
    if ($value['child']) {
      $theArray[] = "child:true";
    }
  }
  if (isset($value['textElement'])) {
    $theArray[] = "textElement:'" . $value['textElement'] . "'";
  }
  if (isset($value['method'])) {
    $the_method = $value['method'];
    $theArray[] = "method:'" . $value['method'] . "'";
  }
  if ($the_method !== 'set') {
    if (isset($value['duration'])) {
      $theArray[] = "duration:" . $value['duration'];
    }
    if (isset($value['ease'])) {
      $get_ease = $value['ease'];
      $the_ease = null;
      if ($get_ease !== 'custom') {
        $the_ease = $get_ease;
        $theArray[] = "ease:'" . $the_ease . "'";
      } else if (isset($value['customEase'])) {
        $the_ease = $value['customEase'];
        $theArray[] = "customEase:'" . $the_ease . "'";
      }
    }
    if (isset($value['isStagger']) && $value['isStagger']) {
      $the_stagger = null;
      if (isset($value['staggerFrom']) || isset($value['staggerAxis'])) {
        $stagger_opt = [];
        if (isset($value['staggerEach'])) {
          $stagger_opt[] = "each:" . $value['staggerEach'];
        } else {
          $stagger_opt[] = "each:0.1";
        }
        if (isset($value['staggerFrom'])) {
          $stagger_opt[] = "from:'" . $value['staggerFrom'] . "'";
        }
        if (isset($value['staggerAxis']) && $value['staggerAxis'] !== 'default') {
          $stagger_opt[] = "axis:'" . $value['staggerAxis'] . "'";
        }
        $stagger_obj = "{";
        $stagger_obj .= implode(",", $stagger_opt);
        $stagger_obj .= "}";
        $theArray[] = "stagger:" . $stagger_obj;
      } else {
        $the_stagger = 0.1;
        if (isset($value['staggerEach'])) {
          $the_stagger = $value['staggerEach'];
        }
        $theArray[] = "stagger:" . $the_stagger;
      }
    }
  }
  if ($the_method === 'fromTo' && isset($value['from']) && $value['from'] && count($value['from'])) {
    $get_from = $value['from'];
    $from_array = [];
    foreach ($get_from as $property) {
      foreach ($property as $propKey => $propVal) {
        $from_array[] = $propKey . ":'" . $propVal . "'";
      }
    }
    $from_result = 'from:{';
    $from_result .= implode(',', $from_array);
    $from_result .= '}';
    $theArray[] = $from_result;
  }
  if (isset($value['to']) && $value['to'] && count($value['to'])) {
    $get_to = $value['to'];
    $to_array = [];
    foreach ($get_to as $property) {
      foreach ($property as $propKey => $propVal) {
        $to_array[] = $propKey . ":'" . $propVal . "'";
      }
    }
    $to_result = 'to:{';
    $to_result .= implode(',', $to_array);
    $to_result .= '}';
    $theArray[] = $to_result;
  }
  if (isset($value['delayPrevious'])) {
    $theArray[] = "delayPrevious:" . $value['delayPrevious'];
  }
  $generate = '';
  if (count($theArray)) {
    $generate .= '{';
    $generate .= implode(',', $theArray);
    $generate .= '}';
  }
  return $generate;
}

function transformClipVideo($value)
{
  $theArray = [];
  $the_method = 'play';
  $theArray[] = "clipType:'video'";
  if (isset($value['target'])) {
    $get_target = $value['target'];
    if (!count($get_target)) return;
    if (count($get_target)) {
      $the_target = "[";
      $the_target .= "'" . implode("','", $get_target) . "'";
      $the_target .= "]";
      $theArray[] = "target:" . $the_target;
    }
  }
  if (isset($value['child'])) {
    if ($value['child']) {
      $theArray[] = "child:true";
    }
  }
  if (isset($value['method'])) {
    $the_method = $value['method'];
    $theArray[] = "method:'" . $the_method . "'";
  }
  if (isset($value['isJump']) && $value['isJump']) {
    $theArray[] = "isJump:true";
    if ($the_method === 'sync') {
      if (isset($value['totalTime'])) $theArray[] = "totalTime:" . $value['totalTime'];
      if (isset($value['timeFrom'])) $theArray[] = "timeFrom:" . $value['timeFrom'];
      if (isset($value['timeTo'])) $theArray[] = "timeTo:" . $value['timeTo'];
    } else {
      if (isset($value['timeFrom'])) $theArray[] = "timeFrom:" . $value['timeFrom'];
    }
  }
  if (isset($value['duration'])) $theArray[] = "duration:" . $value['duration'];
  if (isset($value['delayPrevious'])) {
    $theArray[] = "delayPrevious:" . $value['delayPrevious'];
  }
  $generate = '';
  if (count($theArray)) {
    $generate .= '{';
    $generate .= implode(',', $theArray);
    $generate .= '}';
  }
  return $generate;
}

function transformClipLottie($value)
{
  $theArray = [];
  $the_method = 'play';
  $theArray[] = "clipType:'lottie'";
  if (isset($value['target'])) {
    $get_target = $value['target'];
    if (!count($get_target)) return;
    if (count($get_target)) {
      $the_target = "[";
      $the_target .= "'" . implode("','", $get_target) . "'";
      $the_target .= "]";
      $theArray[] = "target:" . $the_target;
    }
  }
  if (isset($value['child'])) {
    if ($value['child']) {
      $theArray[] = "child:true";
    }
  }
  if (isset($value['method'])) {
    $the_method = $value['method'];
    $theArray[] = "method:'" . $the_method . "'";
  }
  if (isset($value['isJump']) && $value['isJump']) {
    $theArray[] = "isJump:true";
    if ($the_method === 'sync') {
      if (isset($value['totalFrame'])) $theArray[] = "totalFrame:" . $value['totalFrame'];
      if (isset($value['frameFrom'])) $theArray[] = "frameFrom:" . $value['frameFrom'];
      if (isset($value['frameTo'])) $theArray[] = "frameTo:" . $value['frameTo'];
    } else {
      if (isset($value['frameFrom'])) $theArray[] = "frameFrom:" . $value['frameFrom'];
    }
  }
  if (isset($value['duration'])) $theArray[] = "duration:" . $value['duration'];
  if (isset($value['delayPrevious'])) {
    $theArray[] = "delayPrevious:" . $value['delayPrevious'];
  }
  $generate = '';
  if (count($theArray)) {
    $generate .= '{';
    $generate .= implode(',', $theArray);
    $generate .= '}';
  }
  return $generate;
}
// function transformTriggerValue($key, $value, $is_timeline)
// {
//   $optionsArray = [];
//   foreach ($value as $item) {
//     if ($item['enable']) {
//       $settings = $item['custom'];
//       switch ($item['triggerItem']) {
//         case 'animation':
//           $get_value = transformTriggerAnimationItem($settings, $is_timeline);
//           if ($get_value) $optionsArray[] = $get_value;
//           break;
//         case 'function':
//           $optionsArray[] = transformTriggerFuncItem($settings);
//           break;
//       }
//     }
//   }
//   $generate = '';
//   if (count($optionsArray)) {
//     $generate .= $key . ':{';
//     $generate .= implode(',', $optionsArray);
//     $generate .= '}';
//   }
//   return $generate;
// }

// function transformTriggerAnimationItem($theValue, $is_timeline)
// {
//   $optionsArray = [];
//   $the_timeline = $theValue['timeline'];
//   if ($is_timeline) $the_timeline = true;
//   if ($the_timeline) {
//     if (!$is_timeline) $optionsArray[] = 'timeline:true';
//     if (isset($theValue['delay']) && $theValue['delay']) $optionsArray[] = 'delay:' . $theValue['delay'];
//     if (isset($theValue['repeat']) && $theValue['repeat']) $optionsArray[] = 'repeat:' . $theValue['repeat'];
//     if (isset($theValue['repeatDelay']) && $theValue['repeatDelay']) $optionsArray[] = 'repeatDelay:' . $theValue['repeatDelay'];
//     if (isset($theValue['reverse']) && $theValue['reverse']) $optionsArray[] = 'reverse:true';
//   }
//   if (isset($theValue['tweens']) && count($theValue['tweens'])) {
//     $tweensArray = [];
//     foreach ($theValue['tweens'] as $item) {
//       if (!isset($item['to']) || !count($item['to'])) return;
//       if ($item['type'] === 'fromTo' && !count($item['from'])) return;
//       $tweenOpts = [];
//       if (isset($item['child'])) $tweenOpts[] = "child:'" . $item['child'] . "'";
//       $the_type = $item['type'];
//       if ($the_type) $tweenOpts[] = "type:'" . $the_type . "'";
//       if (isset($item['duration'])) $tweenOpts[] = "duration:" . $item['duration'];
//       if (isset($item['delay']) && $item['delay']) $tweenOpts[] = "delay:" . $item['delay'];
//       $the_ease = $item['ease'];
//       $the_custom_ease = $item['customEase'];
//       if ($the_ease && $the_ease !== 'custom') {
//         $tweenOpts[] = "ease:'" . $the_ease . "'";
//       } else if ($the_ease === 'custom' && $the_custom_ease) {
//         $tweenOpts[] = "ease:'" . $the_custom_ease . "'";
//       }
//       if (isset($item['stagger']) && $item['stagger']) $tweenOpts[] = "stagger:0.1";
//       if ($the_timeline && isset($item['tlPosition'])) $tweenOpts[] = "tlPosition:'" . $item['tlPosition'] . "'";
//       if (isset($item['from']) && $the_type === 'fromTo') {
//         $from_value = $item['from'];
//         $from_array = [];
//         foreach ($from_value as $propItem) {
//           foreach ($propItem as $propKey => $propVal) {
//             $from_array[] = $propKey . ":'" . $propVal . "'";
//           }
//         }
//         if (count($from_array)) {
//           $from_result = 'from:{';
//           $from_result .= implode(',', $from_array);
//           $from_result .= '}';
//           $tweenOpts[] = $from_result;
//         }
//       }
//       $to_value = $item['to'];
//       $to_array = [];
//       foreach ($to_value as $propItem) {
//         foreach ($propItem as $propKey => $propVal) {
//           $to_array[] = $propKey . ":'" . $propVal . "'";
//         }
//       }
//       if (count($to_array)) {
//         $to_result = 'to:{';
//         if ($the_type === 'from') $to_result = 'from:{';
//         $to_result .= implode(',', $to_array);
//         $to_result .= '}';
//         $tweenOpts[] = $to_result;
//       }
//       if (count($tweenOpts)) {
//         $tweenResult = '{';
//         $tweenResult .= implode(',', $tweenOpts);
//         $tweenResult .= '}';
//         $tweensArray[] = $tweenResult;
//       }
//     }
//     if (count($tweenResult)) {
//       $tweensResult = 'tweens:[';
//       $tweensResult .= implode(',', $tweensArray);
//       $tweensResult .= ']';
//       $optionsArray[] = $tweensResult;
//     }
//   }
//   $generate = '';
//   if (count($optionsArray)) {
//     $generate .= 'ani:{';
//     $generate .= implode(',', $optionsArray);
//     $generate .= '}';
//   }
//   return $generate;
// }

// function transformTriggerFuncItem($theValue)
// {
//   $generate = "fn:'console.log();'";
//   return $generate;
// }

// function transformChildTriggerValue($value)
// {
//   $optionsArray = [];
//   foreach ($value as $item) {
//     if ($item['enable']) {
//       switch ($item['triggerItem']) {
//         case 'animation':
//           $optionsArray[] = transformChildTriggerAnimationItem($item['custom']);
//           break;
//         case 'function':
//           $optionsArray[] = transformTriggerFuncItem($item['custom']);
//           break;
//       }
//     }
//   }

//   $generate = '';
//   if (count($optionsArray)) {
//     $generate .= '{';
//     $generate .= implode(',', $optionsArray);
//     $generate .= '}';
//   }
//   return $generate;
// }

// function transformChildTriggerAnimationItem($value)
// {
//   $optionsArray = [];
//   if (isset($value['delay'])) $optionsArray[] = 'delay:' . $value['delay'];
//   if (isset($value['repeat'])) $optionsArray[] = 'repeat:' . $value['repeat'];
//   if (isset($value['repeatDelay'])) $optionsArray[] = 'repeatDelay:' . $value['repeatDelay'];
//   if (isset($value['reverse']) && $value['reverse']) $optionsArray[] = 'reverse:true';
//   if (isset($value['tweens'])) {
//     $the_tweens = $value['tweens'];
//     $tweens_opt = [];
//     foreach ($the_tweens as $tween) {
//       $tween_array = [];
//       if (isset($tween['to'])) {
//         $ease = null;
//         $custom_ease = null;
//         if (isset($tween['child'])) $tween_array[] = "child:'" . $tween['child'] . "'";
//         if (isset($tween['type'])) $tween_array[] = "type:'" . $tween['type'] . "'";
//         if (isset($tween['duration'])) $tween_array[] = "duration:" . $tween['duration'];
//         if (isset($tween['delay'])) $tween_array[] = "delay:" . $tween['delay'];
//         if (isset($tween['tlPosition'])) $tween_array[] = "tlPosition:'" . $tween['tlPosition'] . "'";
//         if (isset($tween['ease'])) $ease = $tween['ease'];
//         if (isset($tween['customEase'])) $custom_ease = $tween['customEase'];
//         if ($custom_ease && $ease === 'custom') {
//           $tween_array[] = "ease:'" . $custom_ease . "'";
//         } else if ($ease && $ease !== 'custom') {
//           $tween_array[] = "ease:'" . $ease . "'";
//         }
//         if (isset($tween['stagger']) && $tween['stagger']) $tween_array[] = "stagger:true";
//         if (isset($tween['from']) && isset($tween['type']) && $tween['type'] === 'fromTo') {
//           $from_setting = $tween['from'];
//           $from_array = [];
//           foreach ($from_setting as $from_item) {
//             foreach ($from_item as $propKey => $propVal) {
//               $from_array[] = $propKey . ":'" . $propVal . "'";
//             }
//           }
//           if (count($from_array)) {
//             $from_result = 'from:{';
//             $from_result .= implode(',', $from_array);
//             $from_result .= '}';
//             $tween_array[] = $from_result;
//           }
//         }
//         $to_setting = $tween['to'];
//         $to_array = [];
//         foreach ($to_setting as $to_item) {
//           foreach ($to_item as $propKey => $propVal) {
//             $to_array[] = $propKey . ":'" . $propVal . "'";
//           }
//         }
//         if (count($to_array)) {
//           $to_result = 'to:{';
//           $to_result .= implode(',', $to_array);
//           $to_result .= '}';
//           $tween_array[] = $to_result;
//         }
//       }
//       if (count($tween_array)) {
//         $tween_opt = '{' . implode(',', $tween_array) . '}';
//         $tweens_opt[] = $tween_opt;
//       }
//     }
//     $optionsArray[] = 'tweens:[' . implode(',', $tweens_opt) . ']';
//   }
//   $generate = '';
//   if (count($optionsArray)) {
//     $generate .= 'animation:{';
//     $generate .= implode(',', $optionsArray);
//     $generate .= '}';
//   }
//   return $generate;
// }

function transformAnimationTween($tweens)
{
  if (!count($tweens)) return '';
  $theArray = [];
  foreach ($tweens as $tween) {
    $the_tween_settings = [];
    $the_method = 'to';
    error_log(print_r($tween, true));
    if (isset($tween['child'])) {
      $the_method = $tween['child'];
      $the_tween_settings[] = "child:'" . $tween['child'] . "'";
    }
    if (isset($tween['method'])) {
      $the_method = $tween['method'];
      $the_tween_settings[] = "method:'" . $tween['method'] . "'";
    }
    if ($the_method !== 'set') {
      if (isset($tween['duration'])) {
        $the_tween_settings[] = "duration:" . $tween['duration'];
      }
      if (isset($tween['delay'])) {
        $the_tween_settings[] = "delay:" . $tween['delay'];
      }
      if (isset($tween['ease'])) {
        $get_ease = $tween['ease'];
        $the_ease = null;
        if ($get_ease !== 'custom') {
          $the_ease = $get_ease;
          $the_tween_settings[] = "ease:'" . $the_ease . "'";
        } else if (isset($tween['customEase'])) {
          $the_ease = $tween['customEase'];
          $the_tween_settings[] = "customEase:'" . $the_ease . "'";
        }
      }
      if (isset($tween['isStagger']) && $tween['isStagger']) {
        $the_stagger = null;
        if (isset($tween['staggerFrom']) || isset($tween['staggerAxis'])) {
          $stagger_opt = [];
          if (isset($tween['staggerEach'])) {
            $stagger_opt[] = "each:" . $tween['staggerEach'];
          } else {
            $stagger_opt[] = "each:0.1";
          }
          if (isset($tween['staggerFrom'])) {
            $stagger_opt[] = "from:'" . $tween['staggerFrom'] . "'";
          }
          if (isset($tween['staggerAxis']) && $tween['staggerAxis'] !== 'default') {
            $stagger_opt[] = "axis:'" . $tween['staggerAxis'] . "'";
          }
          $stagger_obj = "{";
          $stagger_obj .= implode(",", $stagger_opt);
          $stagger_obj .= "}";
          $the_tween_settings[] = "stagger:" . $stagger_obj;
        } else {
          $the_stagger = 0.1;
          if (isset($tween['staggerEach'])) {
            $the_stagger = $tween['staggerEach'];
          }
          $the_tween_settings[] = "stagger:" . $the_stagger;
        }
      }
    }
    if ($the_method === 'fromTo' && isset($tween['from']) && $tween['from'] && count($tween['from'])) {
      $get_from = $tween['from'];
      $from_array = [];
      foreach ($get_from as $property) {
        foreach ($property as $propKey => $propVal) {
          $from_array[] = $propKey . ":'" . $propVal . "'";
        }
      }
      $from_result = 'from:{';
      $from_result .= implode(',', $from_array);
      $from_result .= '}';
      $the_tween_settings[] = $from_result;
    }
    if (isset($tween['to']) && $tween['to'] && count($tween['to'])) {
      $get_to = $tween['to'];
      $to_array = [];
      foreach ($get_to as $property) {
        foreach ($property as $propKey => $propVal) {
          $to_array[] = $propKey . ":'" . $propVal . "'";
        }
      }
      $to_result = 'to:{';
      $to_result .= implode(',', $to_array);
      $to_result .= '}';
      $the_tween_settings[] = $to_result;
    }
    $generate = '';
    if (count($the_tween_settings)) {
      $generate .= '{';
      $generate .= implode(',', $the_tween_settings);
      $generate .= '}';
    }
    if ($generate) $theArray[] = $generate;
  }
  $generate = '';
  if (count($theArray)) {
    $generate .= 'animationTweens:[';
    $generate .= implode(',', $theArray);
    $generate .= ']';
  }
  return $generate;
}
