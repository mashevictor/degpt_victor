<?php

function interactSearchForm($data)
{
  $setting = $data['Desktop'];
  $is_toggle = null;
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'isToggle':
        $is_toggle = $item['custom'];
        break;
    }
  }

  $generate = '';
  if ($is_toggle) {
    $generate .= ',{isToggle:true}';
  }
  return $generate;
}
