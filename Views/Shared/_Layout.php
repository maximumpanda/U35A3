<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . "/Session.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style>
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/Views/Shared/Style.css";
        ?>
    </style>
</head>
<body>
    <?php
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Views/Shared/Header.html";
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Views/Shared/Menu.php";
    ?>
    <div id="ViewContainer">
    <?php
        Session::SetView("/Views/Error/Index.html");
        include_once(Session::$View);
    ?>
    </div>
    <?php
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Views/Shared/Footer.html";
    ?>
</body>
</html>