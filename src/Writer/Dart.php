<?php

namespace Birsoz\Converter\Writer;

use Birsoz\Converter\Writer;

class Dart extends Writer
{
    protected $parser;

    public function __construct($parser)
    {
        $this->parser = $parser;
    }

    public function write(){
        ob_start();
        $writer = $this;
        $parser = $this->parser;
        include $this->getClassTemplatePath();
        $content = ob_get_contents();
        var_dump($content);die;
    }


    protected function getClassTemplatePath(){
        return 'lib/views/dart/struct.phtml';
    }

}