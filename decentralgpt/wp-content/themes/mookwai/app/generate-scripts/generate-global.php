<?php

function generateGlobalScripts($theDatas, $reusableDatas, $lenisLerp)
{
  $generate = 'new mke.LightBox();';
  if ($lenisLerp) {
    $generate .= 'new mke.LenisSmooth(' .$lenisLerp. ');';
  }
  foreach ($theDatas as $key => $value) {
    foreach ($value as $item) {
      $blockID = $item['slug'];
      $blockID = str_replace('-', '_', $blockID);
      foreach ($item['elements'] as $ele => $eleValue) {
        $blockSelector = "{$blockID}";
        if ($ele !== 'root') {
          $thisSelector = $eleValue['selector'];
          if (is_array($thisSelector)) {
            $blockSelector = [];
            if ($eleValue['isRoot']) {
              foreach ($thisSelector as $item) {
                $blockSelector[] = "{$item}.{$blockID}";
              }
            } else {
              foreach ($thisSelector as $item) {
                $blockSelector[] = ".{$blockID} {$item}";
              }
            }
          } else {
            $blockSelector = '';
            if ($eleValue['isRoot']) {
              $blockSelector = "{$thisSelector}.{$blockID}";
            } else {
              $blockSelector = ".{$blockID} {$thisSelector}";
            }
          }
        }
      }
    }
  }

  foreach ($reusableDatas as $item) {
    $blockID = $item['blockID'];
    $blockID = str_replace('-', '_', $blockID);
    foreach ($item['elements'] as $ele => $eleValue) {
      $blockSelector = "{$blockID}";
      if ($ele !== 'root') {
        $thisSelector = $eleValue['selector'];
        if (is_array($thisSelector)) {
          $blockSelector = [];
          if ($eleValue['isRoot']) {
            foreach ($thisSelector as $item) {
              $blockSelector[] = "{$item}.{$blockID}";
            }
          } else {
            foreach ($thisSelector as $item) {
              $blockSelector[] = ".{$blockID} {$item}";
            }
          }
        } else {
          $blockSelector = '';
          if ($eleValue['isRoot']) {
            $blockSelector = "{$thisSelector}.{$blockID}";
          } else {
            $blockSelector = ".{$blockID} {$thisSelector}";
          }
        }
      }
    }
  }
  return $generate;
}
