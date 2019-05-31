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
use Birsoz\Converter\Parser;
use Birsoz\Converter\Struct;

class CSharp extends Parser
{

    function sourceHasNamespaces(): bool
    {
        return true;
    }

    function getNamespaceFromSource(): Nspace
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


            $arg = new Argument($nameWord, $this->getArgumentTypeFromKeyword($typeWord));

            $arg->canBeNull(substr($typeWord,-1) == "?");

            $args[] = $arg;
        }
        return $args;
    }

    protected function getArgumentTypeFromKeyword($keyword): ArgumentType
    {
        if(substr($keyword,-1) == "?"){
            $keyword = substr($keyword,0,-1);
        }
        switch ($keyword) {
            case "char":
            case "string":
                return ArgumentType::String();
            case "int":
                return ArgumentType::Int();
            case "decimal":
                return ArgumentType::Decimal();
            case "Date":
                return ArgumentType::Decimal();
            case "DateTime":
                return ArgumentType::Decimal();
            default:
                return ArgumentType::Object("OBJ".$keyword);
        }
    }
}
