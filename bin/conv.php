#!/usr/bin/env php
<?php

define('BASE_PATH', dirname(__DIR__));
define('EXEC_PATH', getcwd());

require BASE_PATH . '/vendor/autoload.php';

function is_valid_config()
{
    return true;
}

unset ($argv[0]);


$poosibleConfigFile = false;
$config = false;
$possibleConfig = [];
$poosibleConfigFile = EXEC_PATH . '/conv.json';
if (!file_exists(EXEC_PATH . '/conv.json')) {
    $poosibleConfigFile = false;
}

if ($poosibleConfigFile) {
    $possibleConfig = json_decode(file_get_contents($poosibleConfigFile));
    if (is_valid_config($possibleConfig)) {
        $config = (array)$possibleConfig;
    }
}

if (!$config) {
    $config = [];
}

if (!array_key_exists('target', $config)) {
    $config["target"] = 'export';
}


$sourceDir = rtrim($config["source"], '/') . '/';

if (substr($sourceDir, 0, 1) != '/') {
    $sourceDir = EXEC_PATH . '/' . $sourceDir;
}

if (!is_dir($sourceDir)) {
    throw new \Exception("Cannot find source '$sourceDir'");
}
$targetDir = rtrim($config["target"], '/') . '/';

if (substr($targetDir, 0, 1) != '/') {
    $targetDir = EXEC_PATH . '/' . $targetDir;
}

if (!is_dir($targetDir)) {
    throw new \Exception("Cannot find target '$sourceDir'");
}


if (!$config["source_ext"]) {
    throw new \Exception("Missing configuration : 'source_ext");
}

if (!$config["target_ext"]) {
    throw new \Exception("Missing configuration : 'target_ext");
}

switch ($config['source_ext']){
    case 'ts':
        $parser = new Birsoz\Converter\Parser\TypeScript();
        break;
    case 'cs':
        $parser = new Birsoz\Converter\Parser\CSharp();
        break;
    default:
        throw new \Exception("Invalid source_ext : '" . $config['source_ext'] . ".  Available values are 'ts' and 'cs' ");
        break;
}
switch ($config['target_ext']){
    case 'dart':
        $writer = new \Birsoz\Converter\Writer\Dart();
        break;
    default:
        throw new \Exception("Invalid source_ext : '" . $config['target_ext'] . ".  Available values is 'dart'");
        break;
}

$filesArr =scandir($sourceDir);
foreach ($filesArr as $sourceFile){
    if($sourceFile[0]==".")
        continue;
    if($config["source_ext"] != substr($sourceFile,-1* strlen($config["source_ext"]) )){
        echo "Skipping $sourceFile\n";
        continue;
    }
    $sourceFilePath = $sourceDir .  $sourceFile;
    $targetFilePath = $targetDir .  substr($sourceFile,0,-1* strlen($config["source_ext"])).$config['target_ext'];
    echo "Running for $sourceFile\n";
    $structs = $parser->parse($sourceFilePath);
    $writer->write( $structs );
    $writer->writeToFile( $structs ,$targetFilePath);
}


