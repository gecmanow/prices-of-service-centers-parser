<?php

error_reporting(-1);

require_once dirname(__DIR__) . '/config/init.php';
require_once LIBS . '/functions.php';

header("Content-type: text/html; charset=utf-8");

$url = 'https://remont.killprice24.ru';
$content = connect($url);

/*
 * ТЗ - получить данные вида девайс/модель/услуга-цена в виде массива, а из массива вставить их в теблицу Excel
 * массив вида [x, y, z], где x - модели, y - услуги, x*y - цены, z - девайсы
 * а точнее
 * [0, y+1, z] - услуги, [x+1, 0, z] - модели, [x+1, y+1, z] - цены, [0, 0, z] - девайсы
 *
 * получаем html из url
 *
 * получаем искомый элемент из html
 * повторяем до тех пор, пока не получим все элементы
 *
 * формиформируем массив из полученных элементов
 *
 *
 * вставляем найденный элемент в таблицу
 *
 * */


for ($z = 0; $z <= $deviceCount; $z++) {
    $devices = '.product_heading';
    $devicelist = parse($content, $devices, 0, 0, $z);
    $deviceCount = count($devicelist);

    for ($x = 0; $x <= $modelCount; $x++) {
        $models = '.block960 .poduct1024_item .model_list .model_parent:eq(' . $x . ') .model_item';
        $modellist = parse($content, $models, $x+1, 0, $z);
        $modelCount = count($modellist);
        for ($y = 0; $y <= $count; $y++) {
            $names = '.block960 .poduct1024_item .model_list .model_parent:eq(' . $y . ') .model_serv .serv_item .head_price .si_head';
            $namelist = parse($content, $names, $x, $y+1, $z);

            $prices = '.block960 .poduct1024_item .model_list .model_parent:eq(' . $y . ') .model_serv .serv_item .head_price .price1024';
            $pricelist = parse($content, $prices, $x+1, $y+1, $z);

            $count = count($namelist);
        }
    }
}



/*$devices = '.product_heading';
$devicelist = parse($content, $devices);

$prices = '.block960 .poduct1024_item .model_list .model_parent:eq(0) .model_serv .serv_item .head_price .price1024';
$pricelist = parse($content, $prices);

$names = '.block960 .poduct1024_item .model_list .model_parent:eq(0) .model_serv .serv_item .head_price .si_head';
$namelist = parse($content, $names);

$models = '.block960 .poduct1024_item .model_list .model_parent:eq(0) .model_item';
$modellist = parse($content, $models);

debug($devicelist);
debug($namelist);
debug($pricelist);
debug($modellist);*/
