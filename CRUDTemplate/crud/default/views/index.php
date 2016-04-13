<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams     = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\helpers\Url;
use <?=$generator->indexWidgetType === 'grid' ? "yii\\grid\\GridView" : "yii\\widgets\\ListView"?>;

/* @var $this yii\web\View */
<?=!empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : ''?>
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="<?=Inflector::camel2id(StringHelper::basename($generator->modelClass))?>-index">

<div class="dashboard-header">
    <a class="toolbar" multi-choose-mode>
        <i class="fa fa-check-square-o"></i>
    </a>
    <?="<?php"?> if($searchModel->searched) <?="{?>\n";?>
    <a class="toolbar" action-bk2bsearch="" style="margin-right: 10px">
        <i class="fa fa-chevron-left"></i>
    </a>
    <?="<?php"?> } <?="?>"?>

    <a class="toolbar" data-toggle="collapse" data-target="#search-collapse">
        <i class="fa fa-search"></i>
    </a>
    <a class="toolbar pull-right" data-load="#dashboard-content" data-url="<?="/" . Inflector::camel2id(StringHelper::basename($generator->modelClass)) . "/create";?>">
        <i class="fa fa-plus"></i>
    </a>
</div>
<?php if (!empty($generator->searchModelClass)): ?>
<div class="collapse" id="search-collapse">
<?="    <?php " . ($generator->indexWidgetType === 'grid' ? "// " : "")?>echo $this->render('_search', ['model' => $searchModel]); ?>
</div>
<?php endif;?>


<?php if ($generator->indexWidgetType === 'grid'): ?>
    <?="<?= "?>GridView::widget([
        'dataProvider' => $dataProvider,
        <?=!empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n        'columns' => [\n" : "'columns' => [\n";?>
            ['class' => 'yii\grid\SerialColumn'],

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
