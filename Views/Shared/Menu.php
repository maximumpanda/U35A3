<div id="Menu">
    <?php
    include_once $_SERVER["DOCUMENT_ROOT"] . "/RouteTable.php";
    $buttons = [];
    foreach (RouteTable::$Routes as $routeRoot => $branch){
        if (in_array($routeRoot, RouteTable::$HiddenBranches)) continue;
        array_push($buttons, $routeRoot);
    }
    sort($buttons );
    foreach ($buttons as $button) {
        print '<a href="/' . $button . '" class="Element"">' . $button . '</a>';
    }

    ?>
</div>