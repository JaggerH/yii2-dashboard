    <?php
use jackh\aurora\SideBar;
?>
<div class="dashboard-menu">
    <div class="dashboard-banner">
        <h3>Dashboard</h3>
    </div>
<?=SideBar::widget([
    "column"  => [
        "首页" => [
            "options" => [
                "data-load" => "#dashboard-content",
                "data-url"  => "/dashboard/default/overview",
                "class"     => "selected",
                "expanded"  => "true",
            ],
        ],
        "新闻" => [
            "submenu" => [
                "企业新闻" => [
                    "data-load" => "#dashboard-list",
                    "data-url"  => "/news/index",
                ],
                "公司推荐" => [
                    "data-load" => "#dashboard-list",
                    "data-url"  => "/news/index",
                ],
            ],
        ],
        "按钮" => [
            "submenu" => [
                "按钮尺寸"  => [
                    "data-load" => "#dashboard-list",
                    "data-url"  => "/news/index",
                ],
                "按钮尺寸1" => [
                    "data-load" => "#dashboard-list",
                    "data-url"  => "/news/index",
                ],
                "按钮尺寸2" => [
                    "data-load" => "#dashboard-list",
                    "data-url"  => "/news/index",
                ],
            ],
        ],
    ],
    "options" => [
        "class" => "aurora-sidebar",
        "id"    => "menu-sidebar",
    ],
]);
?>
</div>
<div class="dashboard-main"  expanded="true">
    <div class="dashboard-list" id="dashboard-list"></div>
    <div class="dashboard-content">
        <div class="dashboard-header">
            <div class="toolbar pull-right">
                <i class="fa fa-sign-out"></i>
            </div>
        </div>
        <div class="collapse" aria-expanded="false" id="dashboard-tips-handler"></div>
        <div id="dashboard-content">
            <?=$this->render('overview');?>
        </div>
    </div>
</div>
