<?php

function interactLottie($data)
{
  $setting = $data['Desktop'];
  $renderer = null;
  $autoplay = null;
  $loop = null;
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'renderer':
        $renderer = $item['custom'];
        break;
      case 'autoplay':
        $autoplay = $item['custom'];
        break;
      case 'loop':
        $loop = $item['custom'];
        break;
    }
  }

  $generate = '';
  $optionsArray = [];
  if ($renderer === 'canvas') $optionsArray[] = "renderer:'canvas'";
  if ($autoplay) $optionsArray[] = "autoplay:true";
  if ($loop) $optionsArray[] = "loop:true";
  if (count($optionsArray)) {
    $generate .= ',{';
    $generate .= implode(',', $optionsArray);
    $generate .= '}';
  }
  return $generate;
}
