<?php
namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver; 
use App\database\Connection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


//definindo o cabeçalho para utf-8
header("Content-type: text/html; charset=utf-8");


require_once 'vendor/autoload.php';

$database = new Connection();
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();


/**
 * capturar todas as informações exibidas
 *  na tabela e armazenar em um banco de dados
 */
$url = 'https://testpages.herokuapp.com/styled/tag/table.html'; 

/**
 * Host default
 */
$host = 'http://localhost:4444/wd/hub'; 

/**
 * o driver como chrome
 */
$capabilities = DesiredCapabilities::chrome();

/**
 * criando uma conexão com o webdriver
 */
$driver = RemoteWebDriver::create($host, $capabilities, 5000);

/**
 * acessando a pagina
 */
$driver->get($url);

$names = $driver->findElements(WebDriverBy::tagName("td"));

foreach($names as $rows) {    
    $data [] =  $rows->getText();        
}

$datas = [
    [
      'name' => $data[0],
      'amount' => $data[1],
    ],
    [
        'name' => $data[2],
        'amount' => $data[3],
    ],
    [ 
        'name' => $data[4],
        'amount'=> $data[5],
    ],
    [
        'name' => $data[6],
        'amount' => $data[7]
    ]
];

/**
 * Inserir dos dados
 */
$database->ConnInsert($datas);


/**
 * Preencher o formulário e retornar se preenchimento foi ok ou não
 */

$driver->get(" https://testpages.herokuapp.com/styled/basic-html-form-test.html");

$username = $driver->findElement(WebDriverBy::name("username"))->sendKeys('Matias');
$password = $driver->findElement(WebDriverBy::name("password"))->sendKeys('123456');
$comments = $driver->findElement(WebDriverBy::name("comments"))->sendKeys('Vamos lá que da certo !');
$filename = $driver->findElement(WebDriverBy::name("filename"))->sendKeys('/home/leonardo/Documentos/TechLead-Leonardo/CleanCode_PHP.pdf');
/**
 * pega os checkboxes
 */
$checkboxe = $driver->findElement(WebDriverBy::name("checkboxes[]"));
$check = new WebDriverCheckboxes($checkboxe);
$check->selectByIndex(0);

/**
 * pega o radio button
 */
$radiobutton = $driver->findElement(WebDriverBy::name("radioval"));
$radio = new WebDriverRadios($radiobutton);
$radio->selectByIndex(0);

/**
 * multipleselect 
 */
$multipleselect = $driver->findElement(WebDriverBy::name("multipleselect[]"));
$multiple = new WebDriverSelect($multipleselect);
$multiple->selectByValue('ms1');

/**
 * Select dropdown
 */
$selectdropdown = $driver->findElement(WebDriverBy::name("dropdown"));
$dropdown = new WebDriverSelect($selectdropdown);
$dropdown->selectByValue('dd6');

/**
 * enviar formulario
 */
$driver->findElement(WebDriverBy::name('submitbutton'))->submit();

/**
 * Baixar o arquivo através do link
 */
$driver->get("https://testpages.herokuapp.com/styled/download/download.html");

$fileName = $driver->findElement(WebDriverBy::linkText('Direct Link Download'))->click();
rename('/home/leonardo/Downloads/textfile.txt', '/home/leonardo/Downloads/Teste TKS.txt');


$driver->get("https://testpages.herokuapp.com/styled/file-upload-test.html");
$driver->findElement(WebDriverBy::name("filename"))->sendKeys('/home/leonardo/Downloads/Teste TKS.txt');

$radiobutton = $driver->findElement(WebDriverBy::name("filetype"));
$radio = new WebDriverRadios($radiobutton);
$radio->selectByValue('text');
$driver->findElement(WebDriverBy::name('upload'))->submit();

/**
 * Ler PDF
 */
$parser = new \Smalot\PdfParser\Parser();
$pdf = $parser->parseFile('/var/www/html/rpa-lima/Leitura PDF.pdf');

$paginas = $pdf->getPages();

foreach ($paginas as $pagina) {
    $dadosPDF [] =  $pagina->getText() . PHP_EOL;    
}

$sheet->fromArray($dadosPDF, null, 'A2');
$writer = new Xlsx($spreadsheet);
$writer->save('teste.xlsx');

/**
 * fecha o browser
 */
$driver->close();

  