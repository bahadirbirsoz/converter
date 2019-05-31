<?php

namespace Birsoz\Converter;

class Argument
{

    protected $_canBeNull = false;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return ArgumentType
     */
    public function getType(): ArgumentType
    {
        return $this->type;
    }

    /**
     * @param ArgumentType $type
     */
    public function setType(ArgumentType $type): void
    {
        $this->type = $type;
    }

    protected $name;
    protected $type;

    public function __construct(string $name, ArgumentType $type)
    {
        $this->name = $name;
        $this->type = $type;
    }


    public function is(int $compare){
        return $this->type->is($compare);
    }

    public function canBeNull($bool = null){
        if(is_null($bool)){
            return $this->_canBeNull;
        }else{
            $this->_canBeNull=  $bool;
        }
    }


}