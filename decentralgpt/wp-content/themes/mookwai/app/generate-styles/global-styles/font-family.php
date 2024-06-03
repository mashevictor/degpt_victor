<?php

function GlobalStylesFontFamily($preSelector, $datas)
{
  $stylesReg = '';
  if ($preSelector) return $stylesReg;
  $customFont = $datas['customFont'];
  foreach ($customFont as $item) {
    $familyName = $item['title'];
    $woff = $item['woff'];
    $woff2 = $item['woff2'];
    if ($woff) {
      $woff = explode('@&', $woff);
      $woff = $woff[1];
    }
    if ($woff2) {
      $woff2 = explode('@&', $woff2);
      $woff2 = $woff2[1];
    }
    $weight = isset($item['weight']) ? $item['weight'] : null;
    $style = isset($item['style']) ? $item['style'] : null;
    if ($woff && $woff2) {
      $site_url = untrailingslashit(home_url());
      $woff = str_replace($site_url, '../../..', $woff);
      $woff2 = str_replace($site_url, '../../..', $woff2);
      $stylesReg .= "@font-face{";
      $stylesReg .= 'font-family:"' . $familyName . '";';
      if ($weight) {
        $stylesReg .= "font-weight:$weight;";
      }
      if ($style) {
        $stylesReg .= "font-style:$style;";
      }
      $stylesReg .= 'src:url("' . $woff2 . '")format("woff2"),url("' . $woff . '")format("woff");';
      $stylesReg .= 'font-display:swap;';
      $stylesReg .= "}";
    }
  }
  return $stylesReg;
}
