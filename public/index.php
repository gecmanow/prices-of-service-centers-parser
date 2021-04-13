<?php

error_reporting(-1);

require_once dirname(__DIR__) . '/config/init.php';
require_once LIBS . '/functions.php';

header("Content-type: text/html; charset=utf-8");


$url = 'https://remont.killprice24.ru';
$filename = '/pricelist.xlsx';
$file = WWW . $filename;
$data = parse($url);

$result = writeInExcel($data, $file, $filename);


/*foreach ($data as $device => $deviceName) {
    echo $deviceName['productName'];
    foreach($deviceName['models'] as $model => $modelName) {
        echo $modelName['modelName'];
        foreach($modelName['services'] as $service => $serviceName) {
            echo $serviceName['serviceName'];
            echo $serviceName['servicePrice'];
        }
    }
}*/





/*$products = parse($url);
debug($products);*/


/*
 * ф принимает массив
 * есть переменная $a, изначально равная 0
 * проходимся циклом по массиву
 * если находим значение равное $a - записываем его первым элементом в массив $b[]
 * иначе увеличиваем $a на 1
 * если следующее значение равно $b+1 то записываем его следующим элементом в массив $b[]
 * иначе начинаем созавать новый выходной массив и возвращаемся на шаг 2
 * */
/* 90235468 */
/*function hui($array) {
    $a = 0;
    $result = [];
    foreach($array as $b) {
        if($b == $a) {
            array_push($result);
        } else {
            $a++; //1
            for($c = $a; $c <= $b; $c++) {
                if($c == $b) {
                    array_push($result);
                }
            }
        }
    }
}*/
