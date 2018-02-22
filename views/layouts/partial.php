<?php

/* @var $this \yii\web\View */
/* @var $content string */

use jackh\dashboard\assets\bundles\DashboardAsset;
use jackh\dashboard\Instruction;
use yii\helpers\Html;

DashboardAsset::register($this);
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
  <div class="before-load-backdrop">
    <i class="material-icons loadding">settings</i>
  </div>
  <?=$content;?>
  <?=Instruction::Notify() ?>
  <?=Instruction::Refresh() ?>
  <?=Instruction::PjaxReload() ?>
  <?=Instruction::ModalClose() ?>
<?php $this->endBody();?>
</body>
</html>
<?php $this->endPage();?>
