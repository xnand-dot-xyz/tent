<?php
  $commit = trim(file_get_contents("../.git/refs/heads/main"));

  $text = substr($commit, 0, 7);
  $link = "https://forgejo.sny.sh/sun/Tent/commit/" . $commit;

  $config = [
    // The title of this instance, displayed throughout the front-end. Default: Tent.
    "title" => "Tent",

    // A line of text displayed in the footer. May contain HTML. No default.
    "text" => "Tent © <a href=\"https://sny.sh/\">Sunny</a> and contributors. " .
              "Not affiliated with <a href=\"https://bandcamp.com/\">Bandcamp</a>. " .
              "Running commit <a href=\"" . $link . "\">" . $text . "</a>.",

    // Contents of the identity cookie when logged in to Bandcamp. Allows access to higher quality audio files and purchased tracks. No default.
    "identity" => null
  ];

  unset($commit);
  unset($text);
  unset($link);
?>
