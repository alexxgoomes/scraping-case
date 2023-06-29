<?php

require 'vendor/autoload.php';
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\CssSelector\CssSelectorConverter;

$url = 'https://www.agostinholeiloes.com.br/';

$client = new \GuzzleHttp\Client([
    'base_uri' => 'https://www.agostinholeiloes.com.br/',
    'verify' => false
]);

//criando requisição
$res = $client->request('GET', $url);
$html = ''.$res->getBody();

$crawler = new Crawler($html);

//capturando as informações do site
$items = $crawler->filter('.box-leilao > div')->each(function (Crawler $node, $i){
    $image = $node->filter('img')->attr('src');
    $title = $node->filter('h6')->text();
    $firstDate = $node->filter('.card-text > a > p')->text();
    
    //passando as informações para uma lista
    $item = [
        'image' => $image,
        'title' => $title,
        'firstDate' => $firstDate
    ];
    return $item;
});

//criando arquivo CSV para guardar os dados
$file = fopen('case.csv', 'w');

//fazendo um loop para pegar os dados de todos os elementos do site 
foreach ($items as $item) {
    fputcsv($file, $item);
}

fclose($file);

?>