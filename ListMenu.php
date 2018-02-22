<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace jackh\dashboard;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\base\Component;

class ListMenu extends Component
{
    public static function render($options = []) {
        $defaultOptions = [
            "create" => [
                "data-url" => Url::to(["create"]),
                "data-load" => "#dashboard-content",
                "data-content" => "添加",
                "class" => "btn btn-info btn-round btn-just-icon",
            ],  // why value is array, becuase Url::to(array)
            "delete" => [
                "data-url" => Url::to(["delete"]),
                "multiple-choose-mode" => "",
                "data-content" => "删除",
                "class" => "btn btn-danger btn-round btn-just-icon",
            ],
            "people" => [
                "data-url" => Url::to(["index"]),
                "data-content" => "聊天",
                "class" => "btn btn-success btn-round btn-just-icon",
            ],
            "contacts" => [
                "data-url" => Url::to(["contacts"]),
                
                "data-content" => "通讯",
                "class" => "btn btn-warning btn-round btn-just-icon",
            ]
        ];
        $options = ArrayHelper::merge($defaultOptions, $options);
        return Yii::$app->view->render('@vendor/jackh/yii2-dashboard/views/templates/listmenu', ["options" => $options]);
    }
}
