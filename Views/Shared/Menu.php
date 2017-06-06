<div id="Menu">
    <?php
    include_once $_SERVER["DOCUMENT_ROOT"] . "/RouteTable.php";
    $buttons = [];
    foreach (RouteTable::$Routes as $routeRoot => $branch){
        if (in_array($routeRoot, RouteTable::$HiddenBranches) | $branch['AuthenticationLevel'] == 0) continue;
        array_push($buttons, $routeRoot);
    }
    sort($buttons);?>
    <?php foreach ($buttons as $button): ?>
        <a href=<?="/".$button?> class="Element"><?=Helper::SplitPascalCase($button)?></a>
    <?php endforeach?>
</div>