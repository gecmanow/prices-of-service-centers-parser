<?php


use PHPHtmlParser\Dom;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
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

function writeInExcel($data, $file) {

    if (file_exists($file)) {
        IOFactory::load($file);
    } else {
        // Создаем экземпляр класса Spreadsheet (новую таблицу)
        $spreadsheet = new Spreadsheet();
        $y = 2;
        $x = 'B';
        $z = 0;
        foreach ($data as $products => $product) {
            // Называем активный лист именем продукта

            $spreadsheet->setActiveSheetIndex($z);
            $sheet = $spreadsheet->getActiveSheet();
            foreach($product['models'] as $models => $model) {
                $sheet->setCellValue($x . '1', $model['modelName']);
                foreach($model['services'] as $services => $service) {
                    $sheet->setCellValue('A' . $y, $service['serviceName']);
                    $sheet->setCellValue($x . $y, $service['servicePrice']);
                    $x++;
                }
                $y++;
            }
            $newSheet = new Worksheet($spreadsheet, $product['productName']);
            $spreadsheet->addSheet($newSheet, $z+1);
            $z++;
        }
    }
    $writer = new Xlsx($spreadsheet);
    $writer->save($file);
    echo '<a target="_blank" href="' . $_SERVER['HTTP_HOST'] . '">Скачать</a>';
}
