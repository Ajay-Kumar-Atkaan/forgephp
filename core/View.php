<?php 
declare(strict_types=1);
namespace Core;

class View
{

    public static function htmlView(string $file, array $data = [])
    {
        extract($data,EXTR_SKIP);
        ob_start();
        include basePath() .'/views/' . str_replace('.','/',$file) .'.php'; 

        $content = ob_get_clean();

        echo $content;

    }
}