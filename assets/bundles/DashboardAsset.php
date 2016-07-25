<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace jackh\dashboard\assets\bundles;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class DashboardAsset extends AssetBundle {
	public $sourcePath = '@jackh/dashboard/assets';
	public $baseUrl = '@web';
	public $css = [
		'styles/dashboard.css',
		'//cdn.bootcss.com/font-awesome/4.6.3/css/font-awesome.css',
	];
	public $js = [
		'scripts/dashboard.js',
	];
	public $depends = [
		'jackh\aurora\assets\bundles\AuroraAsset',
	];
}
