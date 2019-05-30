<?php

namespace Birsoz\Converter;

class Struct{

    protected $name;

    /**
     * @var Argument
     */
    protected $arguments;

    protected $nspace;

    public function __construct(string $name)
    {
        $this->name=$name;
    }

    public function addArgument(Argument $argument){
        $this->arguments[] = $argument;
    }

    public function setNamespace(Nspace $nspace){
        $this->nspace = $nspace;
    }

    public function getNspace():Nspace{
        return $this->nspace;
    }

}

