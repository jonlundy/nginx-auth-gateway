<?php namespace Xepto;

// PSR-0 Autoloader
function autoload($className)
{
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strripos($className, '\\')) {
        $firstNsPos = stripos($className, '\\');
        $vendorNS  = substr($className, 0, $firstNsPos);
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        
        $fileName = $vendorNS . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
        $fileName .= str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }

    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    require $fileName;
}
spl_autoload_register('\Xepto\autoload');
