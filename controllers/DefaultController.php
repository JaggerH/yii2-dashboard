<?php

namespace jackh\dashboard\controllers;

use Yii;
use jackh\dashboard\models\ModelHistory;
use jackh\dashboard\models\ModelHistorySearch;

/**
 * DefaultController
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class DefaultController extends \yii\web\Controller {
	/**
	 * Action index
	 */
	public function actionIndex() {
		if (Yii::$app->user->isGuest) {
			return $this->redirect('/site/login');
		}
		Yii::$app->view->registerMetaTag([ 'name' => 'accessToken', 'content' => Yii::$app->user->identity->accessToken ]);
		return $this->render('index');
	}

	public function actionOverview() {
		$searchModel = new ModelHistorySearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->setPagination(["pageSize" => 10]);

		return $this->render('overview', ['dataProvider' => $dataProvider]);
	}

	public function actionHistoryDetail($id) {
		if (($model = ModelHistory::findOne($id)) === null) {
			throw new NotFoundHttpException('The requested page does not exist.');
		}

		return $this->render('history-detail', ["model" => $model]);
	}
}
