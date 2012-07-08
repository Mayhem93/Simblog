<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Kingaa
 * Date: 6/28/12
 * Time: 11:40 PM
 * To change this template use File | Settings | File Templates.
 */

include_once '../libs/JShrink/Minifier.class.php';

header('Content-type: application/javascript');
ob_start('ob_gzhandler');

$javascript = '';
sort($_GET['js']);
$cacheFileName = md5(implode('', $_GET['js'])).'.js';

if (file_exists('cache/js/'.$cacheFileName)) {
    echo file_get_contents('cache/js/'.$cacheFileName);
    ob_end_flush();
    exit();
}

if (!empty($_GET['js'])) {
    foreach($_GET['js'] as $js) {
        if ( file_exists($js) )
            $javascript .= file_get_contents($js);
        else
            exit();
    }
}
else
    exit();

echo \JShrink\Minifier::minify($javascript);

ob_end_flush();