<?php

function interactEventGeneral($data, $triggers, $is_page)
{
  $devices = null;
  $load_assets = null;
  $custom_assets = null;
  $is_toggle = null;
  $the_child = null;
  $start = null;
  $end = null;
  $offset = null;
  $smooth = null;
  $pin = null;
  $markers = null;
  $duration = null;
  $ease = null;
  $custom_ease = null;
  $animations = null;
  $has_animation = [];
  $active_animations = [];
  $optionsArray = [];
  // 获取有效动画
  if (isset($data['animations']) && count($data['animations'])) {
    foreach ($data['animations'] as $animation) {
      if (isset($animation['animationID']) && isset($animation['clips']) && count($animation['clips'])) {
        $has_animation[] = $animation['animationID'];
      }
    }
  }
  // 生成事件触发数据
  if ($triggers) {
    foreach ($triggers as $trigger) {
      if (isset($data[$trigger]) && is_array($data[$trigger])) {
        $trigger_setting = [];
        foreach ($data[$trigger] as $item) {
          if (isset($item['enable']) && $item['enable']) {
            if (isset($item['groupItem']) && $item['groupItem'] === 'toggleClass') {
              if (isset($item['target']) && isset($item['className'])) {
                $trigger_toggle_class_setting = [];
                if (isset($item['action']) && $item['action'] === 'remove') {
                  $trigger_toggle_class_setting[] = "action:'remove'";
                }
                $trigger_toggle_class_setting[] = "target:'" . $item['target'] . "'";
                $trigger_toggle_class_setting[] = "className:'" . $item['className'] . "'";
                $generate_trigger_toggle_class = '';
                if (count($trigger_toggle_class_setting)) {
                  $generate_trigger_toggle_class .= "toggleClass:{";
                  $generate_trigger_toggle_class .= implode(',', $trigger_toggle_class_setting);
                  $generate_trigger_toggle_class .= '}';
                }
                if ($generate_trigger_toggle_class) $trigger_setting[] = $generate_trigger_toggle_class;
              }
            }
            if (isset($item['groupItem']) && $item['groupItem'] === 'animationTween') {
              $the_tweens = isset($item['tweens']) ? $item['tweens'] : [];
              $generate_trigger_animation_tween = transformAnimationTween($the_tweens);
              if ($generate_trigger_animation_tween) $trigger_setting[] = $generate_trigger_animation_tween;
            }
            if (isset($item['groupItem']) && $item['groupItem'] === 'animation') {
              if (isset($item['theAnimation'])) {
                $animation_id = $item['theAnimation'];
                if (in_array($animation_id, $has_animation)) {
                  $active_animations[] = $animation_id;
                  // get settings
                  $trigger_animation_setting = [];
                  $trigger_animation_setting[] = "theAnimation:'{$animation_id}'";
                  if (isset($item['action'])) {
                    $trigger_animation_setting[] = "action:'" . $item['action'] . "'";
                  }
                  if (isset($item['delay'])) {
                    $trigger_animation_setting[] = "delay:" . $item['delay'];
                  }
                  $generate_trigger_animation = '';
                  if (count($trigger_animation_setting)) {
                    $generate_trigger_animation .= "animation:{";
                    $generate_trigger_animation .= implode(',', $trigger_animation_setting);
                    $generate_trigger_animation .= '}';
                  }
                  if ($generate_trigger_animation) $trigger_setting[] = $generate_trigger_animation;
                }
              }
            }
          }
        }
        $generate_trigger = '';
        if (count($trigger_setting)) {
          $generate_trigger .= "{$trigger}:{";
          $generate_trigger .= implode(',', $trigger_setting);
          $generate_trigger .= '}';
        }
        if ($generate_trigger) $optionsArray[] = $generate_trigger;
      }
    }
    if (!count($optionsArray)) return '';
  } else if (isset($data['animations'])) {
    foreach ($data['animations'] as $animation) {
      if (isset($animation['animationID']) && isset($animation['clips']) && count($animation['clips'])) {
        $active_animations[] = $animation['animationID'];
      }
    }
  }
  // 生成通用数据
  foreach ($data as $key => $value) {
    switch ($key) {
      case 'devices':
        $devices = transformDevice($value);
        break;
      case 'loadAssets':
        $load_assets = transformDevice($value);
        break;
      case 'customAssets':
        $custom_assets = transformDevice($value);
        break;
      case 'isToggle':
        $is_toggle = $value;
        break;
      case 'child':
        $the_child = $value;
        break;
      case 'start':
        $start = $value;
        break;
      case 'end':
        $end = $value;
        break;
      case 'smooth':
        $smooth = $value;
        break;
      case 'markers':
        $markers = $value;
        break;
      case 'isPin':
        $pin = $value;
        break;
      case 'offset':
        $offset = $value;
        break;
      case 'duration':
        $duration = $value;
        break;
      case 'ease':
        $ease = $value;
        break;
      case 'customEase':
        $custom_ease = $value;
        break;
      case 'animations':
        $animations = transformAnimations($value, $active_animations, $is_page);
        break;
    }
  }
  if ($devices) $optionsArray[] = "devices:{$devices}";
  if ($load_assets) $optionsArray[] = "loadAssets:{$load_assets}";
  if ($custom_assets) $optionsArray[] = "customAssets:{$custom_assets}";
  if ($is_toggle) $optionsArray[] = "isToggle:true";
  if ($the_child) $optionsArray[] = "child:'{$the_child}'";
  if ($start) $optionsArray[] = "start:'{$start}'";
  if ($end) $optionsArray[] = "end:'{$end}'";
  if ($smooth) $optionsArray[] = "smooth:{$smooth}";
  if ($pin) $optionsArray[] = "pin:true";
  if ($markers) $optionsArray[] = "markers:true";
  if ($offset) $optionsArray[] = "offset:{$offset}";
  if ($duration || $duration === 0) $optionsArray[] = "duration:{$duration}";
  if ($ease) {
    if ($ease !== 'custom') {
      $optionsArray[] = "ease:'" . $ease . "'";
    } else if ($custom_ease) {
      $optionsArray[] = "customEase:'" . $custom_ease . "'";
    }
  }
  if ($animations) $optionsArray[] = "animations:{$animations}";

  // 输出
  $generate = '';
  if (count($optionsArray)) {
    $generate .= '{';
    $generate .= implode(',', $optionsArray);
    $generate .= '}';
  }
  return $generate;
}
