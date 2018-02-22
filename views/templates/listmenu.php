<?php

use yii\helpers\Url;
use yii\helpers\Html;
use jackh\admin\components\Helper;

?>
<div id='list-menu'>
    <?php if (Helper::checkRoute($options['create']['data-url'])): ?>
    <?=Html::tag("button", Html::tag("i", "add", ["class" => "material-icons"]), $options['create']) ?>
    <?php endif; ?>
    <?php if (Helper::checkRoute($options['delete']['data-url'])): ?>
    <?=Html::tag("button", Html::tag("i", "delete", ["class" => "material-icons"]), $options['delete']) ?>
    <?php endif;?>
    <?=Html::tag("button", Html::tag("i", "people", ["class" => "material-icons"]), $options['people']) ?>
</div>
<?php if (Helper::checkRoute($options['delete']['data-url'])): ?>
<div id='multiple-menu'>
    <button class="btn btn-info btn-round btn-just-icon" choose-all data-content="全选">
    <i class="material-icons choose">check_box</i>
    <i class="material-icons unchoose">check_box_outline_blank</i>
    </button>
    <button class="btn btn-danger btn-round btn-just-icon" confirm-delete data-content="删除">
    <i class="material-icons">delete</i>
    </button>
</div>
<?php endif;?>
<button id="list-menu-toggle" class="btn btn-primary btn-round btn-just-icon btn-list-nav" data-content="菜单">
  <i class="material-icons menu">menu</i>
  <i class="material-icons close" style="margin: 1.5px 0; display: none;">reply</i>
</button>
