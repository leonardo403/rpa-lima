<?php
namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\DesiredCapabilities; //chamada à classe de drivers
use Facebook\WebDriver\Remote\RemoteWebDriver; //chamada à classe de WebDriver
use Facebook\WebDriver\Chrome\ChromeOptions;

use App\database\Connection;
use Facebook\WebDriver\Interactions\Touch\WebDriverDownAction;
use Facebook\WebDriver\Interactions\WebDriverActions;

//definindo o cabeçalho para utf-8
header("Content-type: text/html; charset=utf-8");

require_once 'vendor/autoload.php';

$database = new Connection();

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
//$database->ConnInsert($datas);


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
$options = new ChromeOptions();
$options = $options->setExperimentalOption('download.default_directory', '/tmp');
$options->setExperimentalOption("prefs", $options);

$driver->get("https://testpages.herokuapp.com/styled/download/download.html");

$driver->findElement(WebDriverBy::linkText("textfile.txt"))->click();

/**
 * fecha o browser
 */
$driver->close();

  