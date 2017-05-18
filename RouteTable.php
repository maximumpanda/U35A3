<?php
class RouteTable
{
    public static $Routes = [
        "Home" => [
            "Index"
        ],
        "Tracker" => [
            "Index"
        ],
        "UserPortal" => [
            "Index",
            "Login",
            "Logout",
            "History"
        ],
        "Error" => [
            "Index"
        ],
        "Api" => [
            "V1" => [
                "Insert" => [],
                "Update" => [],
                "Delete" => []
            ]
        ]
    ];

    public static $HiddenBranches = [
        "Api",
        "Error"
    ];

    public static function PathToDestination($path){
        $current = self::$Routes;
        foreach ($path as $val){
            if (isset($current[$val])){
                $current = $current[$val];
            }
            else{
                return null;
            }

        }
        return $current;
    }
}