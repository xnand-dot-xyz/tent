<?php
  $title = "Settings";
?>

<?php require_once "../elements/header.php" ?>

<?php
  foreach ($_GET as $name => $value)
    setcookie($name, $value);

  if (count($_GET)) {
    header("Location: " . strtok($_SERVER["REQUEST_URI"], "?"));
    exit();
  };
?>

<h1>Settings</h1>

<form>
  <div>
    <p><b>Theme</b></p>
    <p>Whether to adapt to the system theme or always use a light or dark interface.</p>
    <p>
      <select name="theme">
        <?php
          foreach ([
            "system" => "System",
            "light" => "Light",
            "dark" => "Dark"
          ] as $name => $value) {
            echo "<option value=\"" . $name . "\"";
            if ($_COOKIE["theme"] === $name) echo " selected";
            echo ">" . $value . "</option>";
          };
        ?>
      </select>
    </p>
  </div>

  <input type="submit" value="Save">
</form>

<?php require_once "../elements/footer.php" ?>
