<?php
  require_once "../config/config.php";

  if (!isset($config["title"]))
    $config["title"] = "Tent";

  if (isset($title) && !empty($title))
    $title = $config["title"] . " · " . $title;
  else
    $title = $config["title"];
?>

<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <link rel="icon" href="asset.php?file=icon.svg">
    <link rel="stylesheet" href="asset.php?file=style.css">
  </head>
  <body>
    <header>
      <div class="wrapper">
        <a href=".">
          <img id="icon" src="asset.php?file=icon.svg">
        </a>
        <form action="search.php">
          <input name="query" placeholder="Search..." required>
        </form>
      </div>
    </header>
    <main>
      <div class="wrapper">
