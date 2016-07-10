<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use jackh\admin\components\Helper;
use yii\helpers\Html;
use yii\helpers\Url;
use <?=$generator->indexWidgetType === 'grid' ? "yii\\grid\\GridView" : "yii\\widgets\\ListView"?>;
<?=$generator->enablePjax ? 'use yii\widgets\Pjax;' : ''?>

/* @var $this yii\web\View */
<?=!empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : ''?>
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="<?=Inflector::camel2id(StringHelper::basename($generator->modelClass))?>-index">
<?php if (!$generator->enablePjax): ?>
    <div class="dashboard-header">
    <?php $module_name = Inflector::camel2id(StringHelper::basename($generator->modelClass));?>
    <?='    <?php if (Helper::checkRoute("' . $module_name . '/delete")) {?>' . "\n"?>
        <a class="toolbar" multi-choose-mode>
            <i class="fa fa-check-square-o"></i>
        </a>
    <?='    <?php } ?>' . "\n"?>
        <?="<?php"?> if($searchModel->searched) <?="{?>\n";?>
        <a class="toolbar" action-bk2bsearch="" style="margin-right: 10px">
            <i class="fa fa-chevron-left"></i>
        </a>
        <?="<?php"?> } <?="?>" . "\n"?>

        <a class="toolbar" data-toggle="collapse" data-target="#search-collapse">
            <i class="fa fa-search"></i>
        </a>

    <?='    <?php if (Helper::checkRoute("' . $module_name . '/create")) {?>' . "\n"?>
        <a class="toolbar pull-right" data-load="#dashboard-content" data-url="<?=Inflector::camel2id(StringHelper::basename($generator->modelClass)) . "/create";?>">
            <i class="fa fa-plus"></i>
        </a>
    <?='    <?php } ?>' . "\n"?>
    </div>
    <?php if (!empty($generator->searchModelClass)): ?>
    <div class="collapse" id="search-collapse">
    <?="    <?php " . ($generator->indexWidgetType === 'grid' ? "// " : "")?>echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    <?php endif;?>
<?php endif;?>

    <?php if ($generator->indexWidgetType === 'grid'): ?>
<?=$generator->enablePjax ? '
<div class="panel panel-primary col-sm-12 grid-panel">
    <div class="panel-heading">
        <h3 class="panel-title">' . StringHelper::basename($generator->modelClass) . '</h3>
        <!-- btn-icon -->
        <button class="btn-icon create-button" data-load="#dashboard-modal" data-url="/dashboard/' . Inflector::camel2id(StringHelper::basename($generator->modelClass)) . '/create"><i class="fa fa-plus"></i></button>
    </div>
<?php Pjax::begin(["id" => "' . StringHelper::basename($generator->modelClass) . 'Container", "enablePushState" => false, "enableReplaceState" => false, ]); ?>' : ''?>
        <?="<?= "?>GridView::widget([
            'summary' => false,
            'emptyText' => "暂时没有纪录",
            'dataProvider' => $dataProvider,
    <?php
$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false) {
	foreach ($generator->getColumnNames() as $name) {
		if (++$count < 6) {
			echo "            '" . $name . "',\n";
		} else {
			echo "            // '" . $name . "',\n";
		}
	}
} else {
	foreach ($tableSchema->columns as $column) {
		$format = $generator->generateColumnFormat($column);
		if (++$count < 6) {
			echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
		} else {
			echo "            // '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
		}
	}
}
?>

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    <?=$generator->enablePjax ? '<?php Pjax::end(); ?>' : ''?>
</div>
<?=$generator->enablePjax ? '
<?php
$func_name = "func_" . rand();
$reload_url = "/dashboard/' . Inflector::camel2id(StringHelper::basename($generator->modelClass)) . '/index";
$this->registerJs("
        function $func_name() {
            if(e.url == \'/dashboard/' . Inflector::camel2id(StringHelper::basename($generator->modelClass)) . '/create?fid=$fid\' ||
                e.url == \'/dashboard/' . Inflector::camel2id(StringHelper::basename($generator->modelClass)) . '/update?fid=$fid\')
            $.pjax.reload(\'#' . StringHelper::basename($generator->modelClass) . 'Container\', {url: \'$reload_url\', push: false, replace: false})
        }
        $(document).on(\'aftersubmit.dashboard\', \'#dashboard-modal\', $func_name);
        $(document).on(\'click.dashboard\', \'[action-delete=\"' . Inflector::camel2id(StringHelper::basename($generator->modelClass)) . '\"]\', function(e) {
            var url = $(this).attr(\"data-url\")
            $(this).confirm({
                confirm: function() {
                    $.post(url).success(function(response) {
                        $.pjax.reload(\'#' . StringHelper::basename($generator->modelClass) . 'Container\', {url: \'$reload_url\', push: false, replace: false})
                    })
                }
            })
            $(this).confirm(\'show\')
        })
        $(document).off(\'unload.dashboard\', \'#dashboard-content\',$func_name)
");

?>' . "\n" : '';
?>
    <?php else: ?>
        <?="<?= "?>ListView::widget([
            'dataProvider' => $dataProvider,
            'itemOptions' => ['class' => 'item'],
            'summary' => '',
            'itemView' => function ($model, $key, $index, $widget) {
                $widget->itemOptions = array_merge($widget->itemOptions, [
                    "data-url"  => Url::toRoute(['update', 'id' => $model->id]),
                    "data-delete-url"  => Url::toRoute(['delete', 'id' => $model->id]),
                    "data-load" => "#dashboard-content",
                ]);
                $title = Html::tag("p", Html::encode($model->title), ["class" => "title"]);
                return Html::tag("div", $title, ["class" => "content"]);
            },
            'pager'        => [
                'linkOptions' => ["data-load" => "#dashboard-list"],
            ],
            'emptyText'    => '<div class="text-center" style="margin-top: 120px;"><i class="fa fa-bookmark-o" style="font-size: 40px"></i><h3>' . Yii::t('app', 'no result found') . '</h3></div>',
        ]) ?>
    <?php endif;?>
</div>
