<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace jackh\dashboard\console;

use jackh\dashboard\System;
use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class SystemController extends Controller
{
    /**
     * every 2 mins record once CPU
     * so the record_array length is 30
     */
    public function actionRecordcpu()
    {
        $system          = new System;
        $cpu_cache_index = "jackh_dashboard_CPU";
        $record_array    = Yii::$app->cache->get($cpu_cache_index);
        if ($record_array == false) {
            $record_array   = [];
            $record_array[] = $system->cpu_usage_percent();
        } else {
            if (count($record_array) >= 30) {
                array_pop($record_array);
            }
            array_splice($record_array, 0, 0, $system->cpu_usage_percent());
        }
        Yii::$app->set($cpu_cache_index, $record);
    }

}
