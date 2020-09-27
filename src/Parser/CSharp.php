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

class CSharp extends ParserBase
{

    function sourceHasNamespaces(): bool
    {
        return true;
    }

    public function getNamespaceFromSource(): Nspace
    {
        $keyword = 'namespace';
        $keywordPosition = strpos($this->getSourceCode(), $keyword);
        if ($keywordPosition === false) {
            return new Nspace();
        }

        $namespaceString = substr($this->getSourceCode(), $keywordPosition + 10);
        $namespaceString = substr($namespaceString, 0, strpos($namespaceString, '{'));
        $namespaceString = trim($namespaceString);
        return new Nspace(explode('.', $namespaceString));
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
        $arr = explode('{ get; set; }', $str);
        array_pop($arr);

        foreach ($arr as $item) {
            $list = false;
            $nullable = false;

            $item = trim($item);
            $words = explode(' ', $item);

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

            $nameWord = array_pop($words);
            $typeWord = array_pop($words);

            if(substr($typeWord,-1) == "?"){
                $nullable = true;
                $typeWord = substr($typeWord,0,-1);
            }
            if(substr($typeWord,-2) == "[]"){
                $list = true;
                $typeWord = substr($typeWord,0,-2);
            }

            if(substr($typeWord,0,5)=='List<'){
                $list = true;
                $typeWord = substr($typeWord,5,-1);
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
            case "int":
                return $list ? ArgumentType::IntArray() : ArgumentType::Int();
            case "decimal":
                return $list ?  ArgumentType::DecimalArray() : ArgumentType::Decimal();
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
