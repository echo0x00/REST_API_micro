<?php

/**
 * В случае включения в API результата в формате JSON
 * нужно посылать заголовок браузеру:
 * header('Content-Type: application/json');
 * также, REST API подразумевает использование параметров
 * которые передаются по HTTP, здесь для удобства
 * реализована подстановка из примеров задачи
 * вместо $_GET
 * 
 * PSR-12
 * php version 7.4.3
 * 
 * @category Job_Test
 * @package  GTRF
 * @author   Alex Shakmaev <savproga@mail.ru>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://gtrf.ru/
 */

require_once "./src/API.php";

use Gtrf\RestAPI\API as API;


$fromStr    = 'qwe\\5';
$toStr      = API::unpack($fromStr);
echo "<pre>Unpack from string: " . addslashes($fromStr) . " to string {$toStr} \n </pre>";

$fromStr = 'a4bc2d5e';
$toStr = API::unpack($fromStr);
echo "<pre>Unpack str: {$fromStr} to string {$toStr} \n </pre>";

$fromStr = 'qwe\45';
$toStr = API::unpack($fromStr);
echo "<pre>Unpack str: {$fromStr} to string {$toStr} \n </pre>";

$fromStr = '45';
$toStr = API::unpack($fromStr);
echo "<pre>Unpack str: {$fromStr} to string {$toStr} \n </pre>";

$fromStr = 'abcd';
$toStr = API::unpack($fromStr);
echo "<pre>Unpack str: {$fromStr} to string {$toStr} \n </pre>";

$fromStr = '';
$toStr = API::unpack($fromStr);
echo "<pre>Unpack str: {$fromStr} to string {$toStr} \n </pre>";

$fromStr = 'qwe\4\5';
$toStr = API::unpack($fromStr);
echo "<pre>Unpack str: {$fromStr} to string {$toStr} \n </pre>";

$fromStr = 'qwe44444';
$toStr = API::pack($fromStr);
echo "<pre>Pack from string: {$fromStr} to string {$toStr} \n </pre>";


$fromStr = 'aaaabccddddde';
$toStr = API::pack($fromStr);
echo "<pre>Pack from string: {$fromStr} to string {$toStr} \n </pre>";
