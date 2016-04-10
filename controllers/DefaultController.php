<?php

namespace jackh\dashboard\controllers;

use Yii;

/**
 * DefaultController
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class DefaultController extends \yii\web\Controller
{
    /**
     * Action index
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionOverview()
    {
        return $this->renderPartial('overview');
    }
}
