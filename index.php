<?
require_once __DIR__.'/modules/bitrix/index.php';
require_once __DIR__.'/modules/system/index.php';
require_once __DIR__.'/modules/bitrix/chat/index.php';
require_once __DIR__.'/modules/system/index.php';
require_once __DIR__.'/modules/database/index.php';

use RoBot\Modules\Database;
use RoBot\Modules\System\Statisctic;
use RoBot\Modules\System\Log;
use RoBot\Modules\Bitrix\Request;
use RoBot\Modules\Bitrix\Chat;


$req = Request::GetRequest();

Log::print($req['DOMAIN'], array($req));

$db = new Database();
$license = $db->GetLicense($req['DOMAIN']);
$settings = $db->GetSettings($req['DOMAIN']);

if($req['TYPE'] == "Chat" && (strtotime(date('d-m-Y')) < $license))
{
    Log::print($req['DOMAIN'], 'Added');
    $chat = new Chat($req, $settings);
    $chat->Message();
}
else if($req['TYPE'] == "Interface")
{
    $bot_req = Statisctic::GetStat($req['DOMAIN'], 'bot_request');
    $bot_all = Statisctic::GetStat($req['DOMAIN'], 'bot_all');
    $d = new DateTime();
    $d->setTimestamp($license);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="./Logo50Transperent.png">
    <link rel="icon" type="image/png" href="./Logo50Transperent.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>RoBot - центр автоматизации</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <!-- CSS Files -->
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="./assets/css/now-ui-dashboard.css?v=1.0.1" rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="./assets/demo/demo.css" rel="stylesheet" />
</head>

<body class="">
    <div hidden><?print_r($req);?></div>
    <div class="wrapper ">
        <div class="sidebar" data-color="orange">
            <!--
        Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red | yellow"
    -->
            <div class="logo">
                <a href="https://itbrains.ru/" target="_blank" class="simple-text logo-normal">
                    ITBrains - RoBot
                </a>
            </div>
            <div class="sidebar-wrapper">
                <ul class="nav">
                    <li class="active">
                        <a href="#">
                            <i class="now-ui-icons design_app"></i>
                            <p>Главная</p>
                        </a>
                    </li>
                    <li>
                        <a href="./settings/">
                            <i class="now-ui-icons loader_gear"></i>
                            <p>Настройки</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main-panel">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-transparent  navbar-absolute bg-primary fixed-top">
                <div class="container-fluid">
                    <div class="navbar-wrapper">
                        <div class="navbar-toggle">
                            <button type="button" class="navbar-toggler">
                                <span class="navbar-toggler-bar bar1"></span>
                                <span class="navbar-toggler-bar bar2"></span>
                                <span class="navbar-toggler-bar bar3"></span>
                            </button>
                        </div>
                        <a class="navbar-brand">Статистика обращений к боту</a>
                    </div>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation"
                        aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-bar navbar-kebab"></span>
                        <span class="navbar-toggler-bar navbar-kebab"></span>
                        <span class="navbar-toggler-bar navbar-kebab"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end" id="navigation">
                        <?if($license > strtotime(date('d-m-Y'))) {?>
                            <ul class="navbar-nav" style="border-radius: 10px;background-color: #f96332;">
                                <li class="nav-item">
                                    <a class="nav-link" href="#pablo">
                                        <i class="now-ui-icons users_single-02"></i> Лицензия действительна до:
                                        <b><?=$d->format('d.m.Y')?></b>
                                    </a>
                                </li>
                            </ul>
                        <?} else {?>
                            <ul class="navbar-nav" style="border-radius: 10px;background-color: #ff5050;">
                            <li class="nav-item">
                                <a class="nav-link" href="#pablo">
                                    <i class="now-ui-icons users_single-02"></i> Лицензия истекла:
                                    <b><?=$d->format('d.m.Y')?></b>
                                </a>
                            </li>
                        </ul>
                        <?}?>
                    </div>

                </div>
            </nav>
            <!-- End Navbar -->
            <div class="panel-header panel-header-lg">
                <canvas id="bigDashboardChart"></canvas>
            </div>
            <div class="content">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card card-chart">
                            <div class="card-header">
                                <h4 class="card-title">Провалившихся диалогов</h4>
                                <div class="dropdown">
                                    <button type="button"
                                        class="btn btn-round btn-default dropdown-toggle btn-simple btn-icon no-caret"
                                        data-toggle="dropdown">
                                        <i class="now-ui-icons loader_gear"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#">Узнать больше</a>
                                        <a class="dropdown-item" href="#">Выгрузить статистику</a>
                                        <a class="dropdown-item text-danger" href="#">Отчистить статистику</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-area">
                                    <canvas id="lineChartExample"></canvas>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="stats">
                                    <i class="now-ui-icons arrows-1_refresh-69"></i> Обновлено: 22:41
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card card-chart">
                            <div class="card-header">
                                <h4 class="card-title">Прибыль от сделок</h4>
                                <div class="dropdown">
                                    <button type="button"
                                        class="btn btn-round btn-default dropdown-toggle btn-simple btn-icon no-caret"
                                        data-toggle="dropdown">
                                        <i class="now-ui-icons loader_gear"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#">Узнать больше</a>
                                        <a class="dropdown-item" href="#">Выгрузить статистику</a>
                                        <a class="dropdown-item text-danger" href="#">Отчистить статистику</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-area">
                                    <canvas id="lineChartExampleWithNumbersAndGrid"></canvas>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="stats">
                                    <i class="now-ui-icons arrows-1_refresh-69"></i> Обновлено: 22:41
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card card-chart">
                            <div class="card-header">
                                <h4 class="card-title">Обращений за 24 часа</h4>
                            </div>
                            <div class="card-body">
                                <div class="chart-area">
                                    <canvas id="barChartSimpleGradientsNumbers"></canvas>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="stats">
                                    .
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
      
</body>
<!--   Core JS Files   -->
<script src="./assets/js/core/jquery.min.js"></script>
<script src="./assets/js/core/popper.min.js"></script>
<script src="./assets/js/core/bootstrap.min.js"></script>
<script src="./assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
<!-- Chart JS -->
<script src="./assets/js/plugins/chartjs.min.js"></script>
<!--  Notifications Plugin    -->
<script src="./assets/js/plugins/bootstrap-notify.js"></script>
<!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
<script src="./assets/js/now-ui-dashboard.js?v=1.0.1"></script>
<!-- Now Ui Dashboard DEMO methods, don't include it in your project! -->
<script src="./assets/demo/demo.js?v=1.0.1"></script>
<script>
    $(document).ready(function () {
        // Javascript method's body can be found in assets/js/demos.js
        demo.initDashboardPageCharts(
            [
                <?="
                {$bot_all['01-'.date('Y')]},
                {$bot_all['02-'.date('Y')]},
                {$bot_all['03-'.date('Y')]},
                {$bot_all['04-'.date('Y')]},
                {$bot_all['05-'.date('Y')]},
                {$bot_all['06-'.date('Y')]},
                {$bot_all['07-'.date('Y')]},
                {$bot_all['08-'.date('Y')]},
                {$bot_all['09-'.date('Y')]},
                {$bot_all['10-'.date('Y')]},
                {$bot_all['11-'.date('Y')]},
                {$bot_all['12-'.date('Y')]},
                "?>
            ],
            [],
            [], 
            [<?="
                {$bot_req[0]},
                {$bot_req[1]},
                {$bot_req[2]},
                {$bot_req[3]},
                {$bot_req[4]},
                {$bot_req[5]},
                {$bot_req[6]},
                {$bot_req[7]},
                {$bot_req[8]},
                {$bot_req[9]},
                {$bot_req[10]},
                {$bot_req[11]},
                {$bot_req[12]},
                {$bot_req[13]},
                {$bot_req[14]},
                {$bot_req[15]},
                {$bot_req[16]},
                {$bot_req[17]},
                {$bot_req[18]},
                {$bot_req[19]},
                {$bot_req[20]},
                {$bot_req[21]},
                {$bot_req[22]},
                {$bot_req[23]}"?>]);
                <?if(($license - strtotime(date('d-m-Y'))) < 604800) {?>
                    demo.showNotification('bottom','right', 'Срок действия лицензии истекает <?=$d->format('d.m.Y')?>.<br>Продлить лицензию можно <a style="text-decoration: none; color: #214268;" href="">здесь</a>', 'danger', 60000)
                <?}?>
    });
</script>

</html>
<?}?>