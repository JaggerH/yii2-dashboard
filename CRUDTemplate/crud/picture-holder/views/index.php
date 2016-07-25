<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
$modelClass = StringHelper::basename($generator->modelClass);
$camelModelClass = Inflector::camel2id(StringHelper::basename($generator->modelClass));

echo "<?php\n";
?>

use jackh\admin\components\Helper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;

$uploadButtonId = "<?=$modelClass?>Upload" . rand();
?>
<div class="<?=$camelModelClass?>-index">
    <div class="panel panel-primary col-sm-12 grid-panel">
        <div class="panel-heading">
            <h3 class="panel-title"><?=$modelClass?></h3>
            <!-- btn-icon -->
            <?='<button id="<?=$uploadButtonId?>" class="btn-icon create-button" data-toggle="upload" data-url="/dashboard/' . $camelModelClass . '/upload?bid=<?=$bid?>"><i class="fa fa-plus"></i></button>'?>
        </div>
    <?=
'<?php Pjax::begin([
    	"id" => "' . $modelClass . 'Container",
    	"enablePushState" => false,
    	"enableReplaceState" => false,
    ]);?>
    <?=ListView::widget([
	\'summary\' => \'\',
	\'emptyText\' => "暂时没有纪录",
	\'dataProvider\' => $dataProvider,
	\'itemView\' => function ($model, $key, $index, $widget) {
		$url = "/dashboard/' . $camelModelClass . '/delete?id=$model->id";
		$delete = Html::tag("i", "", [
			"class" => "fa fa-times-circle-o",
			\'action-delete\' => \'' . $camelModelClass . '\',
			\'data-url\' => $url,
		]);
		$deleteHandler = Html::tag("div", $delete, ["class" => "handler"]);
		return Html::tag("div", $deleteHandler, ["style" => "background-image: url(\'" . $model->url . "\')", "class" => "dashboard-image-handler"]);
	},
]);?>
    <?php Pjax::end();?>'
?>
    </div>
</div>

<?="<?php\n";?>
$reload_url = "/dashboard/<?=$camelModelClass?>/index?bid=" . $bid;
$func_name = "func_" . rand();
$this->registerJs("
    function $func_name(e) {
        var url = $(this).attr(\"data-url\")
        $(this).confirm({
            confirm: function() {
                $.post(url).success(function(response) {
                    $.pjax.reload('#<?=$modelClass;?>Container', {url: '$reload_url', push: false, replace: false})
                })
            }
        })
        $(this).confirm('show')
    }
    $(document).on('click.dashboard', '[action-delete=\"<?=$camelModelClass?>\"]', $func_name)
    $(document).on('unload.dashboard', function() {
        $(document).off('click.dashboard', '[action-delete=\"<?=$camelModelClass?>\"]', $func_name)
    })
    $('#$uploadButtonId').upload({
        url: '/dashboard/<?=$camelModelClass?>/upload?bid=$bid',
        name: '<?=$modelClass;?>[imageFile]',
        done: function(e) {
            $.pjax.reload('#<?=$modelClass;?>Container', {url: '$reload_url', push: false, replace: false})
        }
    })
");
<?="?>\n"?>
