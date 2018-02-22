<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace jackh\dashboard;

use Yii;
use yii\helpers\ArrayHelper;

class HtmlProcess
{
    public static function extractIMG($html) {
        preg_match_all('/< *img[^>]*src *= *["\']?([^"\']*)/i', $html, $matches);
        return $matches[1];
    }

    public static function base64_to_jpeg($base64_string, $output_file) {
        $ifp = fopen($output_file, "wb");
        $data = explode(',', $base64_string);
        fwrite($ifp, base64_decode($data[1]));
        fclose($ifp);
        return $output_file;
    }

    public static function saveBase64IMG($html, $destination) {
        $images = self::extractIMG($html);
        $base64ImageList = [];
        $imagesList = [];
        foreach ($images as $image) {
            if (preg_match("/^data\:image\/(jpeg|png);base64/", $image, $matches)) {
                $base64ImageList[] = [
                    "image" => $image,
                    "extension" => $matches[1] == "jpeg" ? "jpg" : $matches[1]
                ];
            }
        }
        foreach ($base64ImageList as $base64Image) {
            $randomName = time() . rand() . "." . $base64Image["extension"];
            $tempPath = '/tmp/' . $randomName;
            self::base64_to_jpeg($base64Image["image"], $tempPath);
            $destPath = $destination . '/' . md5_file($tempPath) . "." . $base64Image["extension"];
            rename($tempPath, $destPath);
            $imagesList[$base64Image["image"]] = $destPath;
        }
        return $imagesList;
    }

    public static function replaceBase64ImageUrl($html, $replaceList, $basePath) {
        $keys = array_keys($replaceList);
        $values = array_map(function($value) use ($basePath) {
            $htmlPath = ltrim($value, str_replace(".", "\.", $basePath));
            return preg_match("/^\//", $htmlPath) ? $htmlPath : "/$htmlPath";
        }, array_values($replaceList));
        return str_replace($keys, $values, $html);
    }

    public static function processHtmlImage($html, $uploadPath, $basePath) {
        $imageKV = HtmlProcess::saveBase64IMG($html, $uploadPath);
        return HtmlProcess::replaceBase64ImageUrl($html, $imageKV, $basePath);
    }

    public static function processParagraph($html, $length = -1) {
        $html = preg_replace('/<img[^>]+>/i', '[图片]', $html);
        $html = strip_tags($html);
        $html = str_replace('&nbsp;', ' ', $html);
        if ($length == -1) {
            return $html;
        } else {
            return mb_substr($html, 0, $length);
        }
    }
}
