<?php
  require_once "../config/config.php";

  if (!isset($config["title"]))
    $config["title"] = "Tent";

  if (isset($title) && !empty($title))
    $title = $config["title"] . " · " . $title;
  else
    $title = $config["title"];

  header("Content-Security-Policy: default-src 'self'; img-src 'self' data:");
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>⛺</text></svg>">
    <link rel="stylesheet" href="asset.php?file=style.css">
  </head>
  <body class="
    <?php
      if (isset($_COOKIE["theme"]) && in_array($_COOKIE["theme"], ["light", "dark"]))
        echo "theme-" . $_COOKIE["theme"];
    ?>
  ">
    <header>
      <div class="wrapper">
        <a href=".">
          ⛺
        </a>
        <form action="search.php">
          <input name="query" placeholder="Search..." required autofocus>
        </form>
        <a href="settings.php">
          ⚙️
        </a>
      </div>
    </header>
    <main tabindex="-1">
      <div class="wrapper">
