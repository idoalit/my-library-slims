<?php
/**
 * @Created by          : Waris Agung Widodo (ido.alit@gmail.com)
 * @Date                : 23/05/20 09.01
 * @File name           : autoload.php
 */

$namespaces = [
    "Klaras\\" => "/src/",
];

foreach ($namespaces as $namespace => $classpaths) {
    if (!is_array($classpaths)) {
        $classpaths = array($classpaths);
    }
    spl_autoload_register(function ($classname) use ($namespace, $classpaths) {
        if (preg_match("#^" . preg_quote($namespace) . "#", $classname)) {
            $classname = str_replace($namespace, "", $classname);
            $filename = preg_replace("#\\\\#", "/", $classname) . ".php";
            foreach ($classpaths as $classpath) {
                $fullpath = __DIR__ . $classpath . "$filename";
                if (file_exists($fullpath)) include_once $fullpath;
            }
        }
    });
}
