#!/usr/bin/env php
<?php


define('BASE_PATH', dirname(__DIR__));
define('EXEC_PATH',getcwd());

require BASE_PATH . '/vendor/autoload.php';

function is_valid_config(){
return true;
}

unset ($argv[0]);


$poosibleConfigFile = false;
$config = false;
$possibleConfig=[];
$poosibleConfigFile = EXEC_PATH.'/conv.json';
if(!file_exists(EXEC_PATH.'/conv.json')){
    $poosibleConfigFile = false;
}

if($poosibleConfigFile){
    $possibleConfig = json_decode(file_get_contents($poosibleConfigFile));
    if(is_valid_config($possibleConfig)){
        $config = (array)$possibleConfig;
    }
}

if(!$config){
    $config = [];
}

if(!array_key_exists('target',$config) ){
    $config["target"] = 'export';
}


$sourceDir = rtrim($config["source"],'/').'/';

if(substr($sourceDir,0,1)!='/'){
    $sourceDir = EXEC_PATH .'/'. $sourceDir;
}

if(!is_dir($sourceDir)){
    throw new \Exception("Cannot find source '$sourceDir'");
}
$targetDir = rtrim($config["target"],'/').'/';

if(substr($targetDir,0,1)!='/'){
    $targetDir = EXEC_PATH .'/'. $targetDir;
}

if(!is_dir($targetDir)){
    throw new \Exception("Cannot find target '$sourceDir'");
}

$filesArr =scandir($sourceDir);
foreach ($filesArr as $sourceFile){
    if($sourceFile[0]==".")
        continue;
    if($config["sourceLang"] != substr($sourceFile,-1* strlen($config["sourceLang"]) )){
        echo "Skipping $sourceFile";
        continue;
    }
    $sourceFilePath = $sourceDir .  $sourceFile;
    $targetFilePath = $targetDir .  substr($sourceFile,0,-1* strlen($config["sourceLang"])).$config['targetLang'];

    $parser = new Birsoz\Converter\Parser\CSharp($sourceFilePath);
    $writer = new \Birsoz\Converter\Writer\Dart($parser);

    $writer->writeToFile($targetFilePath);
}


