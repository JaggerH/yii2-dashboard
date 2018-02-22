<?php
use jackh\dashboard\assets\bundles\DashboardAsset;
use jackh\dashboard\SidebarNav;
use jackh\admin\components\Helper;
use yii\helpers\Url;

DashboardAsset::register($this);
$this->registerJsFile('//cdn.bootcss.com/socket.io/2.0.1/socket.io.js');
$this->registerJsFile('/scripts/socket.io.js', [
    'depends' => 'jackh\dashboard\assets\bundles\DashboardAsset'
]);

function filter($items) {
    $result = [];
    foreach ($items as $item) {
        if (isset($item["items"])) {
            $group = [];
            foreach ($item["items"] as $subitem) {
                if (Helper::checkRoute($subitem["url"][0])) {
                    if(!isset($subitem["linkOptions"]["data-load"])) {
                        $subitem["linkOptions"]["data-load"] = "#dashboard-list";
                    }
                    $group[] = $subitem;
                }
            }
            if (count($group) != 0) {
                $item["items"] = $group;
                $result[] = $item;
            }
        } else if (Helper::checkRoute($item["url"][0])) {
            if(!isset($item["linkOptions"]["data-load"])) {
                $item["linkOptions"]["data-load"] = "#dashboard-list";
            }
            $result[] = $item;
        }
    }
    return $result;
}
$sidebarItems = filter(Yii::$app->params["sidebar"]);
?>
<style>
  body {
    height: 100%;
    width: 100%;
    overflow: hidden;
    background-color: #EEEEEE;
  }
</style>
<div class="wrapper">
    <div class="sidebar" data-color="purple" data-image="/images/sidebar-1.jpg">
        <div class="logo">
            <a href="/" target="_blank" class="simple-text">
                <img src="/images/ushinef-Name.svg" alt="友山基金">
            </a>
        </div>
        <div class="sidebar-wrapper">
            <?=SidebarNav::widget(['items' => $sidebarItems]);?>
        </div>
    </div>

    <nav class="navbar navbar-dashboard">
        <div class="container-fluid">
            <div class="navbar-minimize">
                <button id="minimizeSidebar" class="btn btn-round btn-white btn-fill btn-just-icon">
                <i class="material-icons visible-on-sidebar-regular">more_vert</i>
                <i class="material-icons visible-on-sidebar-mini">view_list</i>
            <div class="ripple-container"></div></button>
            </div>
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">控制面板</a>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#pablo" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="material-icons">person</i>
                            <p style="display: inline"><?=Yii::$app->user->identity->name?></p>
                        </a>
                        <ul class="dropdown-menu">
                            <li> <a href="<?=Url::to(["/site/update-password"])?>" data-load="#dashboard-modal" expanded="maintain">更改密码</a> </li>
                            <li> <a href="<?=Url::to(["/site/logout"])?>">退出登录</a> </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="main-panel">
        <iframe id="dashboard-list"></iframe>
        <iframe id="dashboard-content"></iframe>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <iframe id="dashboard-modal" src></iframe>
            </div>
        </div>
    </div>
</div>
