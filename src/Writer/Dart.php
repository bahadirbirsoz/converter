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



    public function getStaticTypeOf(Argument $argument){
        switch (true){
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

    public function write(){
        ob_start();
        $writer = $this;
        $parser = $this->parser;
        include $this->getClassTemplatePath();
        $content = ob_get_contents();
        var_dump($content);die;
    }


    protected function getClassTemplatePath(){
        return 'lib/views/dart/struct.phtml';
    }

    protected function getTargetArgumentName(Argument $argument){
        return $argument->getName();
    }

    public function getDeclarationLine(Argument $argument){
        return $this->getStaticTypeOf($argument) . ' ' . $this->getTargetArgumentName($argument).';';
    }

    public function getFactoryLine(Argument $argument){
        switch (true){
            case $argument->is(ArgumentType::TYPE_INTEGER):
                return $this->getTargetArgumentName($argument).': json["' . $argument->getName() . '"] == null ? null : int.parse(json["' . $argument->getName() . '"].toString()), ';

            case $argument->is(ArgumentType::TYPE_DECIMAL):
                return $this->getTargetArgumentName($argument).':  json["' . $argument->getName() . '"] == null ? null :  double.parse(json["' . $argument->getName() . '"].toString()), ';

            case $argument->is(ArgumentType::TYPE_STRING):
                return $this->getTargetArgumentName($argument).':  json["' . $argument->getName() . '"] == null ? null :  json["' . $argument->getName() . '"].toString(), ';

            case $argument->is(ArgumentType::TYPE_DATE):
            case $argument->is(ArgumentType::TYPE_DATETIME):
                return $this->getTargetArgumentName($argument).':  json["' . $argument->getName() . '"] == null ? null :  DateTime.parse(json["' . $argument->getName() . '"].toString() ), ';

            case $argument->is(ArgumentType::TYPE_OBJECT):
                return $this->getTargetArgumentName($argument).':  json["' . $argument->getName() . '"] == null ? null :  ' . $argument->getType()->struct->getClassName() . '.fromJson(json["' . $argument->getName() . '"]), ';

            case $argument->is(ArgumentType::TYPE_LISTOF_INTEGER):
                return $this->getTargetArgumentName($argument).':  json["' . $argument->getName() . '"] == null ? null  : new List<' . $argument->getType()->struct->getClassName() . '>.from ( json["' . $argument->getName() . '"].map( (x)=> int.parse( x.toString() ) ) ), ';

            case $argument->is(ArgumentType::TYPE_LISTOF_DECIMAL):
                return $this->getTargetArgumentName($argument).':  json["' . $argument->getName() . '"] == null ? null  : new List<' . $argument->getType()->struct->getClassName() . '>.from ( json["' . $argument->getName() . '"].map( (x)=> double.parse( x.toString() ) ) ), ';

            case $argument->is(ArgumentType::TYPE_LISTOF_STRING):
                return $this->getTargetArgumentName($argument).':  json["' . $argument->getName() . '"] == null ? null  : new List<String>.from ( json["' . $argument->getName() . '"].map( (x)=> x.toString() ) ), ';

            case $argument->is(ArgumentType::TYPE_LISTOF_DATE):
            case $argument->is(ArgumentType::TYPE_LISTOF_DATETIME):
            return $this->getTargetArgumentName($argument).':  json["' . $argument->getName() . '"] == null ? null  : new List<' . $argument->getType()->struct->getClassName() . '>.from ( json["' . $argument->getName() . '"].map( (x)=> DateTime.parse( x.toString() ) ) ), ';

            case $argument->is(ArgumentType::TYPE_LISTOF_OBJECT):
                return $this->getTargetArgumentName($argument).':  json["' . $argument->getName() . '"] == null ? null  : new List<' . $argument->getType()->struct->getClassName() . '>.from ( json["' . $argument->getName() . '"].map( (x)=> '.$argument->getType()->struct->getClassName().'.fromJson(x) ) ), ';

        }
    }

    public function getToJsonLine(Argument $argument){

    }
    public function getConstructorLine(Argument $argument){
        return 'this.'.$this->getTargetArgumentName($argument).' ,';
    }

}