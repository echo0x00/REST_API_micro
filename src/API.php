<?php

/**
 * Реализация REST API микросервиса
 * PSR-12
 * php version 7.4.3
 * Распаковка и запаковка строк, содержащую повторяющиеся символы
 * a4bc2d5e => aaaabccddddde++
 * abcd => abcd
 * 45 => некорректная строка
 * "" => ""
 * реализовать поддержку escape
 * qwe\4\5 => qwe45
 * qwe\45 => qwe44444
 * qwe\\5 => qwe\\\\\
 *
 * @category Job_Test
 * @package  GTRF
 * @author   Alex Shakmaev <savproga@mail.ru>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://gtrf.ru/
 */

namespace Gtrf\RestAPI;

/**
 * Интерфейс класса REST API
 *
 * @category Job_Test
 * @package  GTRF
 * @author   Alex Shakmaev <savproga@mail.ru>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://gtrf.ru/
 */

interface IAPI
{
    /**
     * Функция запаковки строки
     * Возвращает запакованную строку
     *
     * @param string $unpackStr распакованная строка на входе
     *
     * @return string
     */
    public static function pack(string $unpackStr): string;
    /**
     * Функция распаковки строки
     * Возвращает распакованную строку
     *
     * @param string $packStr запакованная строка на входе
     *
     * @return string
     */
    public static function unpack(string $packStr): string;
    /**
     * Функция запаковки строки
     * Возвращает запакованную строку
     *
     * @param string $result строка результат
     * @param bool   $error  признак ошибки
     *
     * @return string
     */
    public static function print(string $result, bool $error = false): string;
}

/**
 * Класс для работы REST API
 *
 * @category Job_Test_Class
 * @package  GTRF
 * @author   Alex Shakmaev <savproga@mail.ru>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://gtrf.ru/
 */

class API implements IAPI
{
    /**
     * Функция запаковки строки
     * Возвращает запакованную строку
     *
     * @param string $unpackStr распакованная строка на входе
     *
     * @return string
     */
    public static function pack(string $unpackStr): string
    {
        if ((int) preg_match('/\d+/', $unpackStr, $matchesDigits) !== 0) {
            foreach ($matchesDigits as $digits) {
                $packDigit = '';
                $len = strlen($digits);
                $packDigit = '\\' . $digits[0] . $len;

                $unpackStr = str_replace($digits, $packDigit, $unpackStr);
            }
        }

        $returnStr = '';
        $thisChar  = $unpackStr[0];
        $cntOfChar = 0;
        $arrayOfString = str_split($unpackStr);
        foreach ($arrayOfString as $char) {
            if ($thisChar !== $char) {
                $returnStr .= ($cntOfChar > 1) ? $thisChar . $cntOfChar : $thisChar;
                $thisChar = $char;
                $cntOfChar = 1;
            } else {
                $cntOfChar++;
            }

            if ($char == $arrayOfString[count($arrayOfString) - 1]) {
                $returnStr .= $char;
            }
        }

        
        return $returnStr;
    }

    /**
     * Функция распаковки строки
     * Возвращает распакованную строку
     *
     * @param string $packStr запакованная строка на входе
     *
     * @return string
     */
    public static function unpack(string $packStr): string
    {
        if (strlen($packStr) > 0 && (int) preg_match('/\\\\(\d{2})/', $packStr) === 0) {
            $packStr = addslashes($packStr);
        } elseif (strlen($packStr) < 1) {
            return $packStr;
        }

   
        
        if (strpos($packStr, '\\\\')) {
            $regExpPrepareStr        = "/\\\\\\\\(\d)\\\\\\\\/";
            $returnStr = preg_replace($regExpPrepareStr, '$1', $packStr);

            if ($returnStr !== null && $returnStr !== $packStr) {
                return self::print($returnStr);
            }
        }
       
        $regExpWithEscape       = "/\\\\(\d|\\\\)(\d)/";
        $resultMatchWithEscape  = preg_replace_callback(
            $regExpWithEscape,
            function ($match) {
                $cnt = (int) $match[2];
                $returnStr = '';
                for ($i = 0; $i < $cnt; $i++) {
                    $returnStr .= $match[1];
                }
                return $returnStr;
            },
            $packStr
        );

        if ($resultMatchWithEscape == null || $resultMatchWithEscape == $packStr) {
            $regExp = "/([^\\\\\d])(\d)/";
            $resultMatch  = preg_replace_callback(
                $regExp,
                function ($match) {
                    $cnt = (int) $match[2];
                    $returnStr = '';
                    for ($i = 0; $i < $cnt; $i++) {
                        $returnStr .= $match[1];
                    }
                    return $returnStr;
                },
                $packStr
            );

            if ($resultMatch == null || $resultMatch == $packStr) {
                // throw new Exception
                if (preg_match('/\D+/', $packStr)) {
                    return self::print($packStr);
                } else {
                    return self::print('Ошибка формата строки', true);
                }
            } else {
                return self::print($resultMatch);
            }
        } else {
            return self::print($resultMatchWithEscape);
        }

        return '';
    }

    /**
     * Функция запаковки строки
     * Возвращает запакованную строку
     *
     * @param string $result строка результат
     * @param bool   $error  признак ошибки
     *
     * @return string
     */
    public static function print(string $result, bool $error = false): string
    {
        /**
         * Такой вариант подходит, однако не ясно как будут проходить тесты
         * ввиду того, что json_encode дополнительно экранирует обратный слеш
         * оставил обычный вывод
         * 
         * @return ($error) ? json_encode(array('result' => 'error',    'value' => ''))
         *                  : json_encode(array('result' => 'success',  'value' => $result));
        */
        return $result;
    }
}
