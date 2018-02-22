<?php

/* @var $this \yii\web\View */
/* @var $content string */

use jackh\dashboard\assets\bundles\DashboardAsset;
use yii\helpers\Html;
?>
<?php $this->beginPage();?>
<!DOCTYPE html>
<html lang="<?=Yii::$app->language;?>">
<head>
    <meta charset="<?=Yii::$app->charset;?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />

    <?=Html::csrfMetaTags();?>
    <title><?=Html::encode(Yii::$app->id);?></title>
    <?php $this->head();?>
</head>
<body>
<?php $this->beginBody();?>
    <?=$content;?>
<?php $this->endBody();?>
</body>
</html>
<?php $this->endPage();?>
