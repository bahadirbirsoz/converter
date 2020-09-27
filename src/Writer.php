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

    public function write($structs)
    {
        ob_start();
        $writer = $this;
        include $this->getClassTemplatePath();
        $content = ob_get_contents();
        echo $content;
        ob_clean();
    }

    public function writeToFile($structs, $targetPath)
    {
        ob_start();
        $writer = $this;
        include $this->getClassTemplatePath();
        $content = ob_get_contents();
        file_put_contents($targetPath, $content);
        echo "Writing " . $targetPath . "\n";
        ob_clean();
    }

    protected abstract function getClassTemplatePath();

}