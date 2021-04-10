<?php


use PHPHtmlParser\Dom;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function debug($data) {
    echo '<pre>' . print_r($data, 1) . '</pre>';
}

function parse($url) {
    $dom = new Dom;
    $dom->loadFromUrl($url);
    // Продукты которые будут на выходе
    $resultProducts = [];
    // Найдем все дом-элементы продуктов
    $productsDOM = $dom->find('.product');
    // Получаем объекты DOMNode
    // Найдем все названия внутри каждого объекта ДОМ-элемента продуктов
    
    foreach ($productsDOM as $product) {
        // Найдем хедер (получаем названия продуктов):
        $headerWrapper = $product->find('.product_heading');
        $header = $headerWrapper->firstChild();

        $productItem = [
            'productName' => $header->text,
            'models' => []
        ];
        // Пробежимся по моделям
        $allModelDOM = $product->find('.model_parent');

        foreach ($allModelDOM as $model) {
            $modelDOMName = $model->find('.model_item');
            // Здесь экземпляр девайса будет:) 
            // В него напихаем услуги. 
            // Услуга это объект, состоящий из названия и цены
            $modelItem = [
                'modelName' => $modelDOMName->text,
                'services' => []
            ];
            // Пройдёмся же по услугам
            $allUslugiDOM = $model->find('.serv_item');
            foreach ($allUslugiDOM as $usluga) {
                $uslugaItem = [
                    'serviceName' => '',
                    'servicePrice' => '',
                ];
                $uslugaItem['serviceName'] = $usluga->find('.si_head')->text;
                $uslugaItem['servicePrice'] = $usluga->find('.price1024')->text;
                array_push(
                    $modelItem['services'],
                    $uslugaItem
                );
            }
            array_push(
                $productItem['models'],
                $modelItem
            );
        }
        array_push(
            $resultProducts,
            $productItem  
        );
    }
    return $resultProducts;
}

function writeInExcel($data, $file, $filename) {

    if (file_exists($file)) {
        IOFactory::load($file);
    } else {
        // Создаем экземпляр класса Spreadsheet (новую таблицу)
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $xPN = 'A';
        $yPN = 1;
        $xMN = 'B';
        $yMN = 1;
        $xSN = 'C';
        $ySN = 1;
        $xSP = 'D';
        $ySP = 1;
        foreach ($data as $products => $product) {
            $sheet->setCellValue($xPN . $yPN, $product['productName']);
            foreach($product['models'] as $models => $model) {
                $sheet->setCellValue($xMN . $yMN, $model['modelName']);
                foreach ($model['services'] as $services => $service) {
                    $sheet->setCellValue($xSN . $ySN, $service['serviceName']);
                    $ySN++;
                }
                foreach($model['services'] as $services => $service) {
                    $sheet->setCellValue($xSP . $ySP, $service['servicePrice']);
                    $ySP++;
                }
                $yMN = $yMN + count($model['services']);
                $yPN = $yPN + count($model['services']);
            }
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save($file);
    }
    echo '<a target="_blank" href="' . $filename . '">Скачать</a>';
}
