<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019-05-31
 * Time: 00:11
 */

namespace Birsoz\Converter\Parser;

use Birsoz\Converter\Nspace;
use Birsoz\Converter\Struct;

abstract class ParserBase
{

    private $sourceCode;

    /**
     * @var Struct[]
     */
    protected $structs;

    /**
     * @var Nspace
     */
    protected $nspace;

    public function __construct()
    {

    }


    public function parse($path){
        if (!file_exists($path)) {
            throw new \Exception("File not found in '$path'");
        }
        $this->loadSourceCode($path);
        if($this->sourceHasNamespaces()){
            $this->nspace = $this->getNamespaceFromSource();
        }

        return $this->getStructsFromSource();

    }

    abstract function sourceHasNamespaces():bool ;

    abstract public function getNamespaceFromSource():Nspace;

    /**
     * @return Struct[]
     */
    public function getStructs(){
        return $this->structs;
    }


    /**
     * @return Struct
     */
    abstract protected function getStructsFromSource();

    private function loadSourceCode($path)
    {
        $this->sourceCode = file_get_contents($path);
    }

    protected function getSourceCode(){
        return $this->sourceCode;
    }

}