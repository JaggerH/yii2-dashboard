<?php

use common\models\User;
use common\models\Valuation;
use common\models\Visit;
use yii\grid\GridView;
use yii\helpers\Json;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
use jackh\dashboard\HtmlProcess;
use jackh\dashboard\models\ModelHistory;

jackh\dashboard\assets\bundles\DashboardAsset::register($this);
$this->registerJs('
	md.initDashboardPageCharts();
');

$volumn = shell_exec("du -sh /code/www/images | awk '{print $1}'");
$userCount = User::find()->count();
$visitCount = Visit::find()->count();
$manageAmount = Yii::$app->db->createCommand('SELECT sum(jjzcjz) as amount from valuation t1, (SELECT product_id, max(date) as date FROM valuation group by product_id) as t2 where t1.product_id = t2.product_id and t1.date = t2.date')
							->queryOne();
$manageAmount = $manageAmount ? $manageAmount["amount"] : "0";
$eightHourVisit = Visit::eightHourVisitStatistics();
$eightHourUser = Visit::eightHourUserStatistics();
$weeklyVisit = Visit::weeklyVisitStatistics();
?>
<script>
var eightHourVisitLabel = <?=Json::encode($eightHourVisit["label"])?>;
var eightHourVisitCount = <?=Json::encode($eightHourVisit["count"])?>;
var eightHourVisitUpLimit = <?=$eightHourVisit["upLimit"] ?>;

var eightHourUserLabel = <?=Json::encode($eightHourUser["label"])?>;
var eightHourUserCount = <?=Json::encode($eightHourUser["count"])?>;
var eightHourUserUpLimit = <?=$eightHourUser["upLimit"] ?>;

var weeklyVisitLabel = <?=Json::encode($weeklyVisit["label"])?>;
var weeklyVisitCount = <?=Json::encode($weeklyVisit["count"])?>;
var weeklyVisitUpLimit = <?=$weeklyVisit["upLimit"] ?>;
</script>
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-3 col-sm-3 col-xs-6">
				<div class="card card-stats">
					<div class="card-header" data-background-color="blue">
						<i class="material-icons">attach_money</i>
					</div>
					<div class="card-content">
						<p class="category">基金资产净值总额</p>
						<h3 class="title"><?=ceil($manageAmount/10000)?>万</h3>
					</div>
					<div class="card-footer">
						<div class="stats">
							<i class="material-icons">update</i>未清盘资金
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-sm-3 col-xs-6">
				<div class="card card-stats">
					<div class="card-header" data-background-color="green">
						<i class="material-icons">account_circle</i>
					</div>
					<div class="card-content">
						<p class="category">注册用户数量</p>
						<h3 class="title"><?=$userCount?></h3>
					</div>
					<div class="card-footer">
						<div class="stats">
							<i class="material-icons">date_range</i> 累计注册用户数
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-sm-3 col-xs-6">
				<div class="card card-stats">
					<div class="card-header" data-background-color="red">
						<i class="material-icons">web</i>
					</div>
					<div class="card-content">
						<p class="category">页面访问量</p>
						<h3 class="title"><?=$visitCount?></h3>
					</div>
					<div class="card-footer">
						<div class="stats">
							<i class="material-icons">local_offer</i>24小时内访问量
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-sm-3 col-xs-6">
				<div class="card card-stats">
					<div class="card-header" data-background-color="orange">
						<i class="material-icons">dns</i>
					</div>
					<div class="card-content">
						<p class="category">已使用存储空间</p>
						<h3 class="title"><?=$volumn?></h3>
					</div>
					<div class="card-footer">
						<div class="stats">
							<i class="material-icons text-danger">warning</i> <a href="https://www.aliyun.com/" target="_blank">增加磁盘空间</a>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-4">
				<div class="card">
					<div class="card-header card-chart" data-background-color="green">
						<div class="ct-chart" id="eightHourVisit"></div>
					</div>
					<div class="card-content">
						<h4 class="title">八小时内页面访问量</h4>
						<p class="category">最近一小时页面访问量
							<span class="<?=$eightHourVisit["growthClass"]?>">
								<i class="<?=$eightHourVisit["iconClass"]?>"></i>
								<?=$eightHourVisit["growthRate"]?>%
							</span>
						</p>
					</div>
					<div class="card-footer">
						<div class="stats">
							<i class="material-icons">access_time</i> 1分钟之前更新
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-4">
				<div class="card">
					<div class="card-header card-chart" data-background-color="orange">
						<div class="ct-chart" id="eightHourUser"></div>
					</div>
					<div class="card-content">
						<h4 class="title">八小时内用户访问量</h4>
						<p class="category">最近一小时用户访问量
							<span class="<?=$eightHourUser["growthClass"]?>">
								<i class="<?=$eightHourUser["iconClass"]?>"></i>
								<?=$eightHourUser["growthRate"]?>%
							</span>
						</p>
					</div>
					<div class="card-footer">
						<div class="stats">
							<i class="material-icons">access_time</i> 1分钟之前更新
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-4">
				<div class="card">
					<div class="card-header card-chart" data-background-color="red">
						<div class="ct-chart" id="weeklyVisitChart"></div>
					</div>
					<div class="card-content">
						<h4 class="title">七天页面访问量</h4>
						<p class="category">本日页面访问量
							<span class="<?=$weeklyVisit["growthClass"]?>">
								<i class="<?=$weeklyVisit["iconClass"]?>"></i>
								<?=$weeklyVisit["growthRate"]?>%
							</span>
						</p>
					</div>
					<div class="card-footer">
						<div class="stats">
							<i class="material-icons">access_time</i> 1分钟之前更新
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12">
				<div class="card">
					<div class="card-header" data-background-color="orange">
						<h4 class="title">操作历史</h4>
						<p class="category">所有操作都会留下记录，请谨慎操作！</p>
					</div>
<style>
	.long-text {
		height: 2em;
		overflow: hidden;
	}
</style>
					<div class="card-content table-responsive">
						<?php Pjax::begin(['id' => 'OperatorContainer']);?>
				        <?=GridView::widget([
				        	'dataProvider' => $dataProvider,
							'tableOptions' => ["class" => "table table-hover"],
							'headerRowOptions' => ["class" => "text-warning"],
				        	'summary' => false,
				        	'emptyText' => "暂时没有操作纪录",
				        	'columns' => [
								[
									 'label' => '时间',
									 'value' => function($row) { return $row->date; }
								],
								[
									 'label' => '操作者',
									 'value' => function ($data) { return $data->user->name; },
								],
								[
									 'label' => '类型',
									 'value' => function($row) { return ModelHistory::typeName()[$row->type]; }
								],
								[
									 'label' => '表名',
									 'value' => function($row) { return $row->table; }
								],
								[
									 'label' => '字段名',
									 'value' => function($row) { return $row->field_name; }
								],
								[
									 'label' => '旧值',
									 'headerOptions' => ["style" => "width: 15%"],
									 'value' => function($row) {
										 if (preg_match('/<(.+?)[\s]*\/?[\s]*>/si', $row->old_value)) {
											 return HtmlProcess::processParagraph($row->old_value, 20);
										 } else return $row->old_value;
									 }
								],
								[
									 'label' => '新值',
									 'headerOptions' => ["style" => "width: 15%"],
									 'value' => function($row) {
										 if (preg_match('/<(.+?)[\s]*\/?[\s]*>/si', $row->new_value)) {
											 return HtmlProcess::processParagraph($row->new_value, 20);
										 } else return $row->new_value;
									 }
								],
				        		[
				        			'class' => 'jackh\material\ActionColumn',
				                    'visibleButtons' => ["view" => true, "delete" => false, "update" => false],
									'headerOptions' => ["style" => "width: 5%"],
				                    'buttons' => [
										"view" => function($url, $model, $key) {
											if (preg_match('/<(.+?)[\s]*\/?[\s]*>/i', $model->old_value)) {
												$options = array_merge([
													'title'      => Yii::t('yii', 'View'),
													'aria-label' => Yii::t('yii', 'View'),
													'data-load'   => '#dashboard-modal',
													'data-url' => Url::to(['/dashboard/default/history-detail', 'id' => $model->id]),
													'expanded' => 'true',
													'modal-size' => 'modal-lg'
												], ['class' => 'btn']);
												Html::addCssClass($options, "btn-info");
												return Html::tag("button", '<i class="material-icons">drafts</i>', $options);
											}
										}
									]
				        		],
				        	],
				        ]);?>
				        <?php Pjax::end();?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
