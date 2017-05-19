<div id="Menu">
    <?php
    include_once $_SERVER["DOCUMENT_ROOT"] . "/RouteTable.php";
    $buttons = [];
    foreach (RouteTable::$Routes as $routeRoot => $branch){
        if (in_array($routeRoot, RouteTable::$HiddenBranches)) continue;
        array_push($buttons, $routeRoot);
    }
    sort($buttons);?>
    <?php foreach ($buttons as $button): ?>
        <a href=<?="/".$button?> class="Element"><?=$button?></a>
    <?php endforeach?>

    ?>
</div>