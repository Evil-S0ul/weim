<?php
/**
 * Created by PhpStorm.
 * User: ttt
 * Date: 2018/7/10
 * Time: 10:58
 */

$router = new \Moon\Routing\Router();
$router->get('/', function(){
    return 'welcome';
});