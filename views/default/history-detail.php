<?php

use yii\helpers\Html;
use yii\helpers\Url;
use jackh\dashboard\models\ModelHistory;

jackh\dashboard\assets\bundles\DashboardAsset::register($this);
?>

<div class="content" style="width: 100%; overflow: hidden">
	<div class="container-fluid">
		<div class="row">
            <div class="col-sm-6 col-xs-12" style="padding: 10px">
                <div class="alert alert-warning">
                    <div class="container">
                        <div class="alert-icon">
                            <i class="material-icons">info_outline</i>
                        </div>
                        <b>旧值</b>
                    </div>
                </div>
                <?=$model->old_value?>
            </div>
            <div class="col-sm-6 col-xs-12" style="padding: 10px">
                <div class="alert alert-info">
    	            <div class="container">
    					<div class="alert-icon">
    						<i class="material-icons">info_outline</i>
    					</div>
    	            	<b>新值</b>
    	            </div>
    	        </div>
                <?=$model->new_value?>
            </div>
        </div>
	</div>
</div>
