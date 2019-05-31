<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019-05-31
 * Time: 00:11
 */

namespace Birsoz\Converter;

abstract class Parser
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

    public final function __construct($path)
    {
        if (!file_exists($path)) {
            throw new \Exception("File not found in '$path'");
        }
        $this->loadSourceCode($path);
        if($this->sourceHasNamespaces()){
            $this->nspace = $this->getNamespaceFromSource();
        }

        $this->structs = $this->getStructsFromSource();

    }

    abstract function sourceHasNamespaces():bool ;

    abstract function getNamespaceFromSource():Nspace;

    /**
     * @return Struct[]
     */
    public function getStructs(){
        return $this->structs;
    }


    /**
     * @return Struct[]
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