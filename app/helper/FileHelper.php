<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 03.01.2016
 */

namespace app\helper;

class FileHelper
{
    public static function write($content, $file_name = 'log', $ext = 'txt')
    {
        try {
            $filePath = rtrim(ROOT_DIR, '/').'/components/logs/'.$file_name.'.'.$ext;

            $date = new \DateTime();
            $text = "--------START ".$date->format('d.m.Y H:i:s')."-------\n";
            $text .= $content;
            $text .= "\n--------END ".$date->format('d.m.Y H:i:s')."-------\n\n\n";

            $h = fopen($filePath, "a");
            fwrite($h, $text);
            fclose($h);
        } catch (\Exception $ex) {

        }
    }

    public static function write2($content, $file_name = 'log', $ext = 'txt')
    {
        try {
            $filePath = rtrim(ROOT_DIR, '/').'/components/logs/'.$file_name.'.'.$ext;

            $h = fopen($filePath, "a");
            fwrite($h, $content);
            fclose($h);
        } catch (\Exception $ex) {

        }
    }

    public static function writeArray($content, $file_name = 'log', $ext = 'txt')
    {
        try {
            $filePath = rtrim(ROOT_DIR, '/').'/components/logs/'.$file_name.'.'.$ext;

            $date = new \DateTime();
            $text = "--------START ".$date->format('d.m.Y H:i:s')."-------\n";
            ob_start();
            print_r($content);
            $text .= ob_get_clean();
            $text .= "--------END ".$date->format('d.m.Y H:i:s')."-------\n\n\n";

            $h = fopen($filePath, "a");
            fwrite($h, $text);
            fclose($h);
        } catch (\Exception $ex) {

        }
    }

    public static function writeException(\Exception $ex, $file_name = 'log', $ext = 'txt')
    {
        if($ex->getCode() > 0) {
            self::writeArray(array(
                'message'   => $ex->getMessage(),
                'line'      => $ex->getLine(),
                'code'      => $ex->getCode(),
                'trace'     => $ex->getTraceAsString()
            ), $file_name, $ext);
        }
    }
}