<div id="Menu">
    <?php
    include_once $_SERVER["DOCUMENT_ROOT"] . "/RouteTable.php";
    foreach (RouteTable::$Routes as $routeRoot => $branch){
        if (in_array($routeRoot, RouteTable::$HiddenBranches)) continue;
        print '<a href="/'.$routeRoot . '" class="Element"">' . $routeRoot . '</a>';
    }
    ?>
</div>