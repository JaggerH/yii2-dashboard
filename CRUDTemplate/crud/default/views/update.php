<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model <?=ltrim($generator->modelClass, '\\')?> */

?>
<div class="<?=Inflector::camel2id(StringHelper::basename($generator->modelClass))?>-update">
    <?=$generator->enablePjax ? '<div class="modal-header">
        <h4 class="modal-title">' . StringHelper::basename($generator->modelClass) . '</h4>
    </div>
    ' : ''?>
    <?="<?= "?>$this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
