<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
	$searchModelAlias = $searchModelClass . 'Search';
}

/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();

echo "<?php\n";
?>

namespace <?=StringHelper::dirname(ltrim($generator->controllerClass, '\\'))?>;

use Yii;
use <?=ltrim($generator->modelClass, '\\')?>;
<?php if (!empty($generator->searchModelClass)): ?>
use <?=ltrim($generator->searchModelClass, '\\') . (isset($searchModelAlias) ? " as $searchModelAlias" : "")?>;
<?php else: ?>
use yii\data\ActiveDataProvider;
<?php endif;?>
use <?=ltrim($generator->baseControllerClass, '\\')?>;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * <?=$controllerClass?> implements the CRUD actions for <?=$modelClass?> model.
 */
class <?=$controllerClass?> extends <?=StringHelper::basename($generator->baseControllerClass) . "\n"?>
{

    public $layout = '@jackh/dashboard/views/layouts/partial.php';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::className(),
                'only' => ['upload'],
                'formatParam' => '_format',
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    /**
     * Lists all <?=$modelClass?> models.
     * @return mixed
     */
    public function actionIndex($bid)
    {
<?php if (!empty($generator->searchModelClass)): ?>
        $searchModel = new <?=isset($searchModelAlias) ? $searchModelAlias : $searchModelClass?>();
        $dataProvider = $searchModel->search(["<?=$searchModelClass?>"] => ["bid" => $bid]);
        $dataProvider->setPagination(["pageSize" => 15]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
<?php else: ?>
        $dataProvider = new ActiveDataProvider([
            'query' => <?=$modelClass?>::find()->where(["bid" => $bid]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'bid' => $bid,
        ]);
<?php endif;?>
    }


    /**
     * Updates an existing <?=$modelClass?> model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * <?=implode("\n     * ", $actionParamComments) . "\n"?>
     * @return mixed
     */
    public function actionUpdate(<?=$actionParams?>)
    {
        $model = $this->findModel(<?=$actionParams?>);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return \Yii::createObject([
                'class' => 'yii\web\Response',
                'format' => \yii\web\Response::FORMAT_JSON,
                'data' => [
                    'message' => Yii::t('app', "Update Success!"),
                    'success' => true,
                    'data' => [
                        'id' => <?=$actionParams?>
                    ],
                ],
            ]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpload($bid) {
        $model = new <?=$modelClass?>;
        if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            $filepath = $model->upload();
            if ($filepath !== false) {
                // file is uploaded successfully
                $model->setScenario('default');
                $model->setAttributes([
                    // "bid" => (int) $bid,
                    "url" => $filepath,
                ]);
                $model->save();
                return [
                    'success' => true,
                    'data' => [
                        'url' => $filepath,
                    ],
                ];
            }
        }
        return ["success" => false];
    }

    /**
     * Deletes an existing <?=$modelClass?> model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * <?=implode("\n     * ", $actionParamComments) . "\n"?>
     * @return mixed
     */
    public function actionDelete(<?=$actionParams?>)
    {
        $this->findModel(<?=$actionParams?>)->delete();
    }

    /**
     * Finds the <?=$modelClass?> model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * <?=implode("\n     * ", $actionParamComments) . "\n"?>
     * @return <?=$modelClass?> the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(<?=$actionParams?>)
    {
<?php
if (count($pks) === 1) {
	$condition = '$id';
} else {
	$condition = [];
	foreach ($pks as $pk) {
		$condition[] = "'$pk' => \$$pk";
	}
	$condition = '[' . implode(', ', $condition) . ']';
}
?>
        if (($model = <?=$modelClass?>::findOne(<?=$condition?>)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
