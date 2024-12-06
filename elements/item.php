<?php
  function echo_item ($link, $image, $text, $description) {
    $image = get_placeholder() ?: $image;

    echo "<a href=\"" . $link . "\">";
    echo "<img src=\"" . $image . "\">";
    echo "<span>";
    echo $text;

    if (isset($description)) {
      echo "<br>";
      echo "<small>";
      echo $description;
      echo "</small>";
    };

    echo "</span>";
    echo "</a>";
  };
?>
