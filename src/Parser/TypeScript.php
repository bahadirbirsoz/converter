<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019-05-31
 * Time: 00:23
 */

namespace Birsoz\Converter\Parser;


use Birsoz\Converter\Argument;
use Birsoz\Converter\ArgumentType;
use Birsoz\Converter\Nspace;
use Birsoz\Converter\Struct;

class TypeScript extends ParserBase
{

    function sourceHasNamespaces(): bool
    {
        return false;
    }

    public function getNamespaceFromSource(): Nspace
    {
        return new Nspace("");
    }


    protected function getStructsFromSource()
    {
        $arr = explode(' class ', $this->getSourceCode());

        unset($arr[0]);
        $classes = [];

        foreach ($arr as $str) {
            $definitionBeginPosition = strpos($str, '{');
            $className = trim(substr($str, 0, $definitionBeginPosition));
            $classBody = substr($str, $definitionBeginPosition + 2);

            $struct = new Struct($className);

            $arguments = $this->getArgumentsFromClassBody($classBody);
            foreach ($arguments as $argument) {
                $struct->addArgument($argument);
            }
            $classes[] = $struct;

        }
        return $classes;
    }

    function getArgumentsFromClassBody($str)
    {
        $args = [];
        $arr = explode("\n", $str);
        array_pop($arr);

        foreach ($arr as $item) {
            $list = false;
            $nullable = false;

            $item = trim($item);
            if(!$item){
                continue;
            }


            if( strstr($item,"constructor(") ){
                break;
            }

            $words = explode(' ', $item);

            if( in_array("}",$words) ){
                break;
            }



            while (($key = array_search(' ', $words)) !== false) {
                unset($words[$key]);
            }
            if (($key = array_search('private', $words)) !== false) {
                unset($words[$key]);
            }
            if (($key = array_search('public', $words)) !== false) {
                unset($words[$key]);
            }
            if (($key = array_search('virtual', $words)) !== false) {
                unset($words[$key]);
            }

            $nameWord = $words[0];
            $typeWord = $words[1];

            if(substr($nameWord,-1) == ":"){
                $nameWord = substr($nameWord,0,-1);
            }

            if(substr($typeWord,-2) == "[]"){
                $list = true;
                $typeWord = substr($typeWord,0,-2);
            }


            if(substr($typeWord,0,6)=='Array<'){
                $list = true;
                $typeWord = substr($typeWord,6,-1);
            }


            $argType = $this->getArgumentTypeFromKeyword($typeWord,$list);
            $arg = new Argument($nameWord, $argType);

            $arg->canBeNull($nullable);

            $args[] = $arg;
        }

        return $args;
    }

    protected function getArgumentTypeFromKeyword($keyword, $list = false): ArgumentType
    {
        switch ($keyword) {
            case "char":
            case "string":
                return $list ? ArgumentType::StringArray() : ArgumentType::String();
            case "number":
                return $list ?  ArgumentType::DecimalArray() : ArgumentType::Decimal();
            case "boolean":
                return $list ?  ArgumentType::BooleanArray() : ArgumentType::Boolean();
            case "Date":
                return $list ? ArgumentType::DatetimeArray() : ArgumentType::Datetime();
            case "DateTime":
                return $list? ArgumentType::DatetimeArray() :  ArgumentType::Datetime();
            default:
                $struct = new Struct($keyword);
                return $list? ArgumentType::ObjectArray($struct) : ArgumentType::Object($struct);
        }
    }
}
