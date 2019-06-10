<?php

namespace Birsoz\Converter\Writer;

use Birsoz\Converter\Argument;
use Birsoz\Converter\ArgumentType;
use Birsoz\Converter\Writer;

class Dart extends Writer
{
    protected $parser;

    public function __construct($parser)
    {
        $this->parser = $parser;
    }


    public function getStaticTypeOf(Argument $argument)
    {
        switch (true) {
            case $argument->is(ArgumentType::TYPE_INTEGER):
                return "int";
            case $argument->is(ArgumentType::TYPE_DECIMAL):
                return "double";
            case $argument->is(ArgumentType::TYPE_STRING):
                return "String";
            case $argument->is(ArgumentType::TYPE_DATE):
            case $argument->is(ArgumentType::TYPE_DATETIME):
                return "DateTime";
            case $argument->is(ArgumentType::TYPE_OBJECT):
                return $argument->getType()->struct->getClassName();

            case $argument->is(ArgumentType::TYPE_LISTOF_INTEGER):
                return 'List<int>';
            case $argument->is(ArgumentType::TYPE_LISTOF_DECIMAL):
                return 'List<decimal>';
            case $argument->is(ArgumentType::TYPE_LISTOF_STRING):
                return 'List<String>';
            case $argument->is(ArgumentType::TYPE_LISTOF_DATE):
            case $argument->is(ArgumentType::TYPE_LISTOF_DATETIME):
                return 'List<DateTime>';
            case $argument->is(ArgumentType::TYPE_LISTOF_OBJECT):
                return 'List<' . $argument->getType()->struct->getClassName() . '>';
        }
    }

    public function write()
    {
        ob_start();
        $writer = $this;
        $parser = $this->parser;
        include $this->getClassTemplatePath();
        $content = ob_get_contents();
        echo $content;
        ob_clean();
    }

    public function writeToFile($targetPath)
    {
        ob_start();
        $writer = $this;
        $parser = $this->parser;
        include $this->getClassTemplatePath();
        $content = ob_get_contents();
        file_put_contents($targetPath, $content);
        echo "Writing " . $targetPath . "\n";
        ob_clean();
    }


    protected function getClassTemplatePath()
    {
        return dirname(dirname(__DIR__)) . '/views/dart/struct.phtml';
    }

    protected function getTargetArgumentName(Argument $argument)
    {
        return $argument->getName();
    }

    public function getDeclarationLine(Argument $argument)
    {
        return $this->getStaticTypeOf($argument) . ' ' . $this->getTargetArgumentName($argument) . ';';
    }

    public function getFactoryLine(Argument $argument)
    {
        switch (true) {
            case $argument->is(ArgumentType::TYPE_INTEGER):
                return $this->getTargetArgumentName($argument) . ': json["' . $argument->getName() . '"] == null ? null : int.parse(json["' . $argument->getName() . '"].toString()), ';

            case $argument->is(ArgumentType::TYPE_DECIMAL):
                return $this->getTargetArgumentName($argument) . ':  json["' . $argument->getName() . '"] == null ? null :  double.parse(json["' . $argument->getName() . '"].toString()), ';

            case $argument->is(ArgumentType::TYPE_STRING):
                return $this->getTargetArgumentName($argument) . ':  json["' . $argument->getName() . '"] == null ? null :  json["' . $argument->getName() . '"].toString(), ';

            case $argument->is(ArgumentType::TYPE_DATE):
            case $argument->is(ArgumentType::TYPE_DATETIME):
                return $this->getTargetArgumentName($argument) . ':  json["' . $argument->getName() . '"] == null ? null :  DateTime.parse(json["' . $argument->getName() . '"].toString() ), ';

            case $argument->is(ArgumentType::TYPE_OBJECT):
                return $this->getTargetArgumentName($argument) . ':  json["' . $argument->getName() . '"] == null ? null :  ' . $argument->getType()->struct->getClassName() . '.fromJson(json["' . $argument->getName() . '"]), ';

            case $argument->is(ArgumentType::TYPE_LISTOF_INTEGER):
                return $this->getTargetArgumentName($argument) . ':  json["' . $argument->getName() . '"] == null ? null  : new List<int>.from ( json["' . $argument->getName() . '"].map( (x)=> int.parse( x.toString() ) ) ), ';

            case $argument->is(ArgumentType::TYPE_LISTOF_DECIMAL):
                return $this->getTargetArgumentName($argument) . ':  json["' . $argument->getName() . '"] == null ? null  : new List<decimal>.from ( json["' . $argument->getName() . '"].map( (x)=> double.parse( x.toString() ) ) ), ';

            case $argument->is(ArgumentType::TYPE_LISTOF_STRING):
                return $this->getTargetArgumentName($argument) . ':  json["' . $argument->getName() . '"] == null ? null  : new List<String>.from ( json["' . $argument->getName() . '"].map( (x)=> x.toString() ) ), ';

            case $argument->is(ArgumentType::TYPE_LISTOF_DATE):
            case $argument->is(ArgumentType::TYPE_LISTOF_DATETIME):
                return $this->getTargetArgumentName($argument) . ':  json["' . $argument->getName() . '"] == null ? null  : new List<DateTime>.from ( json["' . $argument->getName() . '"].map( (x)=> DateTime.parse( x.toString() ) ) ), ';

            case $argument->is(ArgumentType::TYPE_LISTOF_OBJECT):
                return $this->getTargetArgumentName($argument) . ':  json["' . $argument->getName() . '"] == null ? null  : new List<' . $argument->getType()->struct->getClassName() . '>.from ( json["' . $argument->getName() . '"].map( (x)=> ' . $argument->getType()->struct->getClassName() . '.fromJson(x) ) ), ';

        }
    }

    public function getToJsonLine(Argument $argument)
    {
        $begin = '"' . $argument->getName() . '": ' . $this->getTargetArgumentName($argument) . ' == null ? null : ';
        $end = ',';
        $middle = '';
        switch (true) {
            case $argument->is(ArgumentType::TYPE_STRING):
            case $argument->is(ArgumentType::TYPE_INTEGER):
            case $argument->is(ArgumentType::TYPE_DECIMAL):
                $middle = $this->getTargetArgumentName($argument);
                break;
            case $argument->is(ArgumentType::TYPE_OBJECT):
                $middle = $this->getTargetArgumentName($argument) . '.toJson()';
                break;

            case $argument->is(ArgumentType::TYPE_DATE):
                $middle = $this->getTargetArgumentName($argument) . '.toIso8601String().substring(0,10)';
                break;

            case $argument->is(ArgumentType::TYPE_DATETIME):
                $middle = $this->getTargetArgumentName($argument) . '.toUtc().toString().substring(0,19)';
                break;


            case $argument->is(ArgumentType::TYPE_LISTOF_STRING):
            case $argument->is(ArgumentType::TYPE_LISTOF_INTEGER):
            case $argument->is(ArgumentType::TYPE_LISTOF_DECIMAL):
                $middle = 'new List<dynamic>.from(' . $this->getTargetArgumentName($argument) . '.map((x) => x)) ';
                break;

            case $argument->is(ArgumentType::TYPE_LISTOF_DATE):
                $middle = 'new List<dynamic>.from(' . $this->getTargetArgumentName($argument) . '.map((x) => x.toIso8601String().substring(0,10))) ';
                break;

            case $argument->is(ArgumentType::TYPE_LISTOF_DATETIME):
                $middle = 'new List<dynamic>.from(' . $this->getTargetArgumentName($argument) . '.map((x) => x.toUtc().toString().substring(0,19))) ';
                break;

            case $argument->is(ArgumentType::TYPE_LISTOF_OBJECT):
                $middle = 'new List<dynamic>.from(' . $this->getTargetArgumentName($argument) . '.map((x) => x.toJson())) ';
                break;
        }
        return $begin . $middle . $end;
    }

    public function getConstructorLine(Argument $argument)
    {
        return 'this.' . $this->getTargetArgumentName($argument) . ' ,';
    }

}