<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019-05-31
 * Time: 02:47
 */

namespace Birsoz\Converter;


abstract class Writer
{
    abstract public function write();

    abstract public function writeToFile($filePatb);

}