<?php

function debug($data) {
    echo '<pre>' . print_r($data, 1) . '</pre>';
}

function connect($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru-RU; rv:1.7.12) Gecko/20050919 Firefox/1.0.7");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $content = curl_exec($ch);
    curl_close($ch);
    return $content;
}

function parse($content, $query, $x, $y, $z) {
    if ($query == '.product_heading') {
        $doc = phpQuery::newDocument($content);
        $items = $doc->find($query);
        foreach ($items as $z => $item) {
            if ($z <= 3) {
                $pricelist[$z] = trim($item->textContent);
            } else {
                break;
            }
        }
    } else {
        $doc = phpQuery::newDocument($content);
        $items = $doc->find($query);
        foreach ($items as $item) {
            $pricelist[$x][$y][$z] = trim($item->textContent);
        }
    }
    return $pricelist;
}

/*function parse($content, $query, $x, $y, $z) {
    $doc = phpQuery::newDocument($content);
    $items = $doc->find($query);
    foreach ($items as $item) {
        $pricelist[$x][$y][$z] = trim($item->textContent);
    }
    return $pricelist;
}*/
