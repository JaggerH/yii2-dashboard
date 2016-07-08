    <?php
use common\models\News;
use jackh\aurora\SideBar;
?>
<div class="dashboard-menu">
    <div class="dashboard-banner">
        <h3>Dashboard</h3>
    </div>
<?=SideBar::widget([
	"column" => [
		"首页" => [
			"options" => [
				"data-load" => "#dashboard-content",
				"data-url" => "default/overview",
				"class" => "selected",
				"expanded" => "true",
			],
		],
		"基金产品" => [
			"options" => [
				"data-load" => "#dashboard-list",
				"data-url" => "fund/index",
			],
		],
		"新闻" => [
			"submenu" => [
				"公司新闻" => [
					"data-load" => "#dashboard-list",
					"data-url" => "news/index?NewsSearch[type]=" . News::NEWS_COMPANY_NEWS,
				],
				"友山观点" => [
					"data-load" => "#dashboard-list",
					"data-url" => "news/index?NewsSearch[type]=" . News::NEWS_COMPANY_VIEWS,
				],
				"公司公告" => [
					"data-load" => "#dashboard-list",
					"data-url" => "news/index?NewsSearch[type]=" . News::NEWS_COMPANY_NOTIFYS,
				],
				"友山荐文" => [
					"data-load" => "#dashboard-list",
					"data-url" => "news/index?NewsSearch[type]=" . News::NEWS_COMPANY_RECOMMAND,
				],
				"产品公告" => [
					"data-load" => "#dashboard-list",
					"data-url" => "news/index?NewsSearch[type]=" . News::NEWS_FUND_NEWS,
				],
			],
		],
		"基金经理" => [
			"options" => [
				"data-load" => "#dashboard-list",
				"data-url" => "fund-manager/index",
			],
		],
		"公司高管" => [
			"options" => [
				"data-load" => "#dashboard-list",
				"data-url" => "exectives/index",
			],
		],
		"分公司信息" => [
			"options" => [
				"data-load" => "#dashboard-list",
				"data-url" => "company/index",
			],
		],
		"预约信息" => [
			"options" => [
				"data-load" => "#dashboard-list",
				"data-url" => "appointment/index",
			],
		],
		"企业荣誉" => [
			"options" => [
				"data-load" => "#dashboard-list",
				"data-url" => "glory/index",
			],
		],
		"资料上传" => [
			"options" => [
				"data-load" => "#dashboard-list",
				"data-url" => "download/index",
			],
		],
		"权限管理" => [
			"submenu" => [
				"用户授权" => [
					"data-load" => "#dashboard-list",
					"data-url" => "admin/assignment",
				],
				"角色权限" => [
					"data-load" => "#dashboard-list",
					"data-url" => "admin/role",
				],
				"权限编辑" => [
					"data-load" => "#dashboard-list",
					"data-url" => "admin/permission",
				],
				"路径权限" => [
					"data-load" => "#dashboard-content",
					"data-url" => "admin/route",
					"expanded" => "true",
				],
			],
		],
	],
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
        <div class="collapse" aria-expanded="false" id="dashboard-tips-handler"></div>
        <div id="dashboard-content">
            <?=$this->render('overview');?>
        </div>
    </div>
</div>
