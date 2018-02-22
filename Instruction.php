<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace jackh\dashboard;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\base\Component;

/**
*  Yii::$app->session->setFlash("notify", ["type" => ..., "messgae" => ...]);
*  Yii::$app->session->setFlash("refresh", ["target" => ...]);
*  Yii::$app->session->setFlash("pjax-reload", ["target" => ..., "pjaxContainer" => ..., "reloadUrl" => ...]);
*/
class Instruction extends Component
{
    public static function Notify() {
      if (Yii::$app->session->hasFlash("notify")) {
        $options = Yii::$app->session->getFlash("notify");
        return Html::tag("div", "", ArrayHelper::merge(["notify" => ""], $options));
      }
      return "";
    }

    public static function Refresh() {
        if (Yii::$app->session->hasFlash("refresh")) {
            $options = Yii::$app->session->getFlash("refresh");
            return Html::tag("div", "", ArrayHelper::merge(["refresh" => ""], $options));
        }
        return "";
    }

    public static function PjaxReload() {
        if (Yii::$app->session->hasFlash("pjax-reload")) {
            $options = Yii::$app->session->getFlash("pjax-reload");
            return Html::tag("div", "", ArrayHelper::merge(["pjax-reload" => ""], $options));
        }
        return "";
    }

    public static function ModalClose() {
        if (Yii::$app->session->hasFlash("modal-close")) {
            return Html::tag("div", "", ["modal-close" => ""]);
        }
        return "";
    }
}
