    <?php
use jackh\aurora\SideBar;
?>
<div class="dashboard-menu">
    <div class="dashboard-banner">
        <h3>Dashboard</h3>
    </div>
<?=SideBar::widget([
	/** columns
	 *	"页面编辑" => [
	 *		// "options" => [
	 *		//  "data-load" => "#dashboard-content",
	 *		//  "data-url" => "site/index",
	 *		//  "class" => "selected",
	 *		//  "expanded" => "true",
	 *		// ],
	 *		"options" => [
	 *			"data-load" => "#dashboard-list",
	 *			"data-url" => "edite/index",
	 *		],
	 *	],
	 *	"基金产品" => [
	 *		"options" => [
	 *			"data-load" => "#dashboard-list",
	 *			"data-url" => "fund/index",
	 *		],
	 *	],
	 **/
	"column" => Yii::$app->params["dashboardSidebar"],
	"options" => [
		"class" => "aurora-sidebar",
		"id" => "menu-sidebar",
	],
]);
?>
</div>
<div class="dashboard-main"  expanded="true">
    <div class="dashboard-list" id="dashboard-list"></div>
    <div class="dashboard-content">
        <div class="dashboard-header">
            <div class="toolbar pull-right" action-logout>
                <i class="fa fa-sign-out"></i>
            </div>
        </div>
        <div class="collapse" aria-expanded="false" id="content-tips"></div>
        <div id="dashboard-content">
            <?=$this->render(Yii::$app->params['dashboardInitPage']);?>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="dashboard-modal" tabindex="-1" role="dialog" aria-labelledby="dashboard-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    </div>
  </div>
</div>
<div class="collapse dashboard-tips-handler" aria-expanded="false" id="modal-tips"></div>