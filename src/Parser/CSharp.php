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

        $namespaceString = substr($this->getSourceCode(), $keywordPosition+ 10);
        $namespaceString = substr($namespaceString, 0, strpos($namespaceString, '{'));
        $namespaceString = trim($namespaceString);
        return new Nspace(explode('.',$namespaceString));
    }



    function getStructsFromSource()
    {
        $arr = explode(' class ',$this->getSourceCode());
        unset($arr[0]);
        $classes = [];

        foreach ($arr as $str){
            $definitionBeginPosition = strpos($str,'{');
            $className = trim(substr($str,0,$definitionBeginPosition));
            $classBody = substr($str,$definitionBeginPosition);
            $struct =  new Struct($className);

            $arguments = $this->getArgumentsFromClassBody($classBody);
            foreach ($arguments as $argument){
                $struct->addArgument($argument);
            }
            $classes[] = $struct;

        }
        return $classes;
    }

    function getArgumentsFromClassBody($str){
        $args = [];
        $arr = explode('{ get; set; }',$str);
        array_pop($arr);
        unset($arr[0]);

        foreach($arr as $item){
            $item = trim($item);
            $words = explode(' ',$item);
            $args[] = new Argument( $words[2] , $this->getArgumentTypeFromKeyword($words[1]) );
        }
        return $args;
    }

    protected function getArgumentTypeFromKeyword($keyword):ArgumentType{
        return ArgumentType::Datetime();
    }

}