<?php

function pseudoStyles($setting, $init)
{
  // Initialize
  $theStyles = '';
  $foundContent = null;

  // Get Datas
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'content':
        $foundContent = $item;
        break;
    }
  }

  // Generate
  if ($foundContent) {
    $the_value = $foundContent['custom'];
    if (substr($the_value, 0, 7) === "counter" || substr($the_value, 0, 5) === "attr(") {
      $theStyles .= 'content:' . $the_value . ';';
    } else {
      $theStyles .= 'content:"' . $the_value . '";';
    }
  } else if ($init) {
    $theStyles .= 'content:"";';
  }

  return $theStyles;
}
