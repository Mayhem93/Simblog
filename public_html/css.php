<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Kingaa
 * Date: 6/24/12
 * Time: 5:34 PM
 * To change this template use File | Settings | File Templates.
 */

include_once '../libs/CSSmin.php';
header('Content-type: text/css');
ob_start('ob_gzhandler');

$stylesheet = '';
sort($_GET['css']);
$cacheFileName = md5(implode('', $_GET['css'])).'.css';

if (file_exists('cache/css/'.$cacheFileName)) {
    echo file_get_contents('cache/css/'.$cacheFileName);
    ob_end_flush();
    exit();
}

if (!empty($_GET['css'])) {
    foreach($_GET['css'] as $css) {
        if ( file_exists($css) )
            $stylesheet .= file_get_contents($css);
        else
            exit();
    }
}
else
    exit();

echo CssMin::minify($stylesheet);

ob_end_flush();