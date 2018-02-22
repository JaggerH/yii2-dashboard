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
		'//cdn.bootcss.com/font-awesome/4.6.3/css/font-awesome.css',
		'//cdn.bootcss.com/bootstrap-sweetalert/1.0.1/sweetalert.min.css',
		'//cdn.bootcss.com/material-design-icons/3.0.1/iconfont/material-icons.min.css',
		'//cdn.bootcss.com/summernote/0.8.2/summernote.css',
		'/styles/material-dashboard.css',
	];
	public $js = [
		'/scripts/jquery.tagsinput.js',
		'/scripts/upload.min.js',
		'/scripts/material.min.js',
		'/scripts/chartist.min.js',
		'/scripts/chartist-plugin-legend.js',
		'/scripts/bootstrap-notify.js',
		'/scripts/bootstrap-datepicker.js',
		'//cdn.bootcss.com/jquery.perfect-scrollbar/0.6.16/js/perfect-scrollbar.jquery.min.js',
		'//cdn.bootcss.com/summernote/0.8.2/summernote.min.js',
		'//cdn.bootcss.com/summernote/0.8.2/lang/summernote-zh-CN.min.js',
		'//cdn.bootcss.com/bootstrap-sweetalert/1.0.1/sweetalert.min.js',
		'//cdn.bootcss.com/jquery.perfect-scrollbar/0.6.16/js/perfect-scrollbar.jquery.min.js',
		'/scripts/material-dashboard.js'
	];
	public $depends = [
		'yii\bootstrap\BootstrapPluginAsset',
	];
}
