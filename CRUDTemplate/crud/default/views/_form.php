<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
	$safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

use yii\helpers\Html;
use jackh\aurora\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?=ltrim($generator->modelClass, '\\')?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?=$generator->enablePjax ? 'modal-body ' : ''?><?=Inflector::camel2id(StringHelper::basename($generator->modelClass))?>-form">

    <?="<?php "?>$form = ActiveForm::begin(); ?>

<?php foreach ($generator->getColumnNames() as $attribute) {
	if (in_array($attribute, $safeAttributes)) {
		echo "    <?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
	}
}?>
    <?php if ($generator->enablePjax): ?>
    <div class="form-group col-sm-12">
    <?="<?= "?>Html::submitButton($model->isNewRecord ? <?=$generator->generateString('Create')?> : <?=$generator->generateString('Update')?>, ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>
    <?php else: ?>
    <?="<?= "?>Html::submitButton($model->isNewRecord ? <?=$generator->generateString('Create')?> : <?=$generator->generateString('Update')?>, ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary', 'style' => 'position: absolute; top: 20px; left: 30px;']) ?>
    </div>
    <?php endif;?>
    <?="<?php "?>ActiveForm::end(); ?>

</div>
