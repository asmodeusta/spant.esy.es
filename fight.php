<?php
namespace root;

/*  */
defined('ROOT') or define('ROOT', dirname(__FILE__));
defined('APP') or define('APP', ROOT . '/app');
require_once APP . '/models/Fighter.php';

$fighter1 = new Fighter();
$fighter2 = new Fighter();

$result = [];

$result["fighters"] = [
    "f1" => [
        "avatar" => [
            "x" => $fighter1->avatar['x'],
            "y" => $fighter1->avatar['y'],
        ],
        "strength" => $fighter1->strength,
        "agility" => $fighter1->agility,
        "stamina" => $fighter1->stamina,
        "hp" => $fighter1->hp,
    ],
    "f2" => [
        "avatar" => [
            "x" => $fighter2->avatar['x'],
            "y" => $fighter2->avatar['y'],
        ],
        "strength" => $fighter2->strength,
        "agility" => $fighter2->agility,
        "stamina" => $fighter2->stamina,
        "hp" => $fighter2->hp,
    ],
];

$rounds = [];
$end = "";

for($i=1;$i<=70;$i++) {
    $power1 = $fighter1->attack();
    $power2 = $fighter2->attack();
    $damage1 = -$fighter1->hit($power2);
    $damage2 = -$fighter2->hit($power1);
    $hp1= $fighter1->hp;
    $hp2= $fighter2->hp;

    $rounds[$i-1] = [
        "n" => $i,
        "f1" => [
            "ap" => $power1,
            "dp" => $damage1,
            "hp" => $hp1,
        ],
        "f2" => [
            "ap" => $power2,
            "dp" => $damage2,
            "hp" => $hp2,
        ],
    ];

    if($hp1===0&&$hp2===0) {
        $end = "A DRAW!";
        break;
    } elseif($hp1===0) {
        $end = "FIGHTER 2 WON!";
        break;
    } elseif($hp2===0) {
        $end = "FIGHTER 1 WON!";
        break;
    }
}

$result["rounds"] = $rounds;
$result["end"] = $end;

echo json_encode($result);