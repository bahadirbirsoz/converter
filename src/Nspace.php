<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019-05-31
 * Time: 00:30
 */

namespace Birsoz\Converter;


class Nspace
{

    protected $namespaceWords = [];
    public function __construct($namespaceWords)
    {
        $this->namespaceWords = $namespaceWords;
    }
}