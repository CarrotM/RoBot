<?
require_once __DIR__ . '/../modules/database/index.php';
require_once __DIR__ . '/../modules/dialogflow/addkey.php';
require_once __DIR__.'/../modules/bitrix/index.php';


use RoBot\Modules\Bitrix\Request;
use RoBot\Modules\Database;
use RoBot\Modules\Dialogflow\LoadKey;

$req = Request::GetRequest();
$db = new Database();

if (isset($_POST['method'])) {
    if ($_POST['method'] == "SaveSettings") {
        $db->SaveSettings($req['DOMAIN'], $_POST);
    }
    if ($_POST['method'] == "AddKey") {
        if (isset($_FILES)) {
            if ($_FILES['json']['type'] == "application/json") {
                echo LoadKey::LoadFile($req['DOMAIN']);
            } else {
                echo "notaccept";
            }
        } else {
            echo false;
        }
    }
} else {
    $dbres = $db->GetSettings($req['DOMAIN']);
    $license = $db->GetLicense($req['DOMAIN']);
    $d = new DateTime();
    $d->setTimestamp($license);
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <link rel="apple-touch-icon" sizes="76x76" href="../Logo50Transperent.png">
        <link rel="icon" type="image/png" href="../Logo50Transperent.png">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>RoBot - центр автоматизации</title>
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
        <!--     Fonts and icons     -->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
        <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
        <!-- CSS Files -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link href="test.css" rel="stylesheet" />
        <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
        <link href="../assets/css/now-ui-dashboard.css?v=1.0.1" rel="stylesheet" />
        <!-- CSS Just for demo purpose, don't include it in your project -->
        <link href="../assets/demo/demo.css" rel="stylesheet" />
    </head>

    <body class="">
        <div hidden><? print_r($dbres); ?></div>
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
                        <li class="">
                            <a href="../">
                                <i class="now-ui-icons design_app"></i>
                                <p>Главная</p>
                            </a>
                        </li>
                        <li class="active">
                            <a href="#">
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
                            <a class="navbar-brand">Настройка бота</a>
                        </div>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
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
                <div class="panel-header panel-header-sm">
                </div>
                <div class="content">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="title">Настройки бота</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 pr-1">
                                            <div class="form-group">
                                                <label>Приветственное сообщение</label>
                                                <textarea class="form-control" aria-label="Hi" style="border-radius: 10px;border: rgba(0,0,0,0.1) 1px solid;" id="settings-hitext"><?= $dbres['hitext'] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6 pr-1">
                                            <div class="form-group">
                                                <label>Сообщение об ошибке</label>
                                                <textarea class="form-control" aria-label="Error" style="border-radius: 10px;border: rgba(0,0,0,0.1) 1px solid;" id="settings-errortext"><?= $dbres['errortext'] ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row text-center">
                                        <div class="col-12 text-center">
                                            <h4>Режим работы бота</h4>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 pr-1">
                                            <ul class="nav nav-tabs">
                                                <li class="nav-item" disabled>
                                                    <a class="nav-link <? if ($dbres['mode'] == 0) {
                                                                            echo 'active';
                                                                        } ?>" id="steptosteptab" href="#" disabled>Пошаговое меню (в разработке)</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link <? if ($dbres['mode'] == 1) {
                                                                            echo 'active';
                                                                        } ?>" id="aitab" data-toggle="tab" href="#ai">Искуственный
                                                        интеллект</a>
                                                </li>
                                            </ul>

                                            <div class="tab-content">
                                                <div class="tab-pane fade <? if ($dbres['mode'] == 0) {
                                                                                echo 'active show';
                                                                            } ?>" id="steptostep">
                                                    <div class="row border-bottom" id="row1">
                                                        <div class="col-12 text-center">
                                                            <button type="button" class="btn btn-primary rounded-pill addbtn" onclick="$('#row1_1').modal('show');">
                                                                <i class="now-ui-icons ui-1_settings-gear-63"></i> Старт
                                                                <span class="position-absolute start-50 translate-middle bg-danger border border-light rounded-circle addbtnspan" onclick="AddElement(1); $('#row1_1').modal('hide');" style="top: 85% !important;height: 20px; width: 20px; padding: 0px; left: 50% !important; padding-top: 1px;">
                                                                    <i class="now-ui-icons ui-1_simple-add"></i>
                                                                </span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade <? if ($dbres['mode'] == 1) {
                                                                                echo 'active show';
                                                                            } ?>" id="ai">
                                                    <div class="col-6">
                                                        <div class="field__wrapper">

                                                            <input type="file" id="DialogflowFile" class="field field__file">

                                                            <label class="field__file-wrapper" for="DialogflowFile">
                                                                <div class="field__file-fake"><?if($dbres['aikey'] != null) echo "Текущий ключ: {$dbres['aikey']}"; else "Файл не выбран";?></div>
                                                                <div class="field__file-button">Выбрать</div>
                                                            </label>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-10">

                                        </div>
                                        <div class="col-2">
                                            <button id="settings-save" class="btn btn-primary">Сохранить</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card card-user">
                                <div class="image">
                                    <img src="" alt="">
                                </div>
                                <div class="card-body">
                                    <div class="author">
                                        <a href="#">
                                            <img class="avatar border-gray" src="../Logo.png" alt="...">
                                            <h5 class="title">RoBot</h5>
                                        </a>
                                        <p class="description">
                                            ITBrains_RoBot
                                        </p>
                                    </div>
                                    <p class="description text-center">
                                        <?if($dbres['mode'] == 1 && $dbres['aikey'] != null) {?>
                                            Подключен искуственный интеллект
                                        <?} else {?>
                                            Бот еще не настроен
                                        <?}?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="row1_1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Название компонента" value="Старт" required>
                            </div>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Предыдущий компонент</label>
                            <select class="form-control">
                                <option>None</option>
                                <option>Старт</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Ответ пользователю</label>
                            <textarea class="form-control" placeholder="Текст ответа" style="border-radius: 10px;border: rgba(0,0,0,0.1) 1px solid;" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Конвертировать в сделку?</label>
                            <input class="form-check-input ml-2" type="checkbox" value="" id="defaultCheck1">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger">Удалить</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                        <button type="button" class="btn btn-primary">Сохранить</button>
                    </div>
                </div>
            </div>
        </div>

    </body>
    <!--   Core JS Files   -->
    <script src="../assets/js/core/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
    <!-- Chart JS -->
    <script src="../assets/js/plugins/chartjs.min.js"></script>
    <!--  Notifications Plugin    -->
    <script src="../assets/js/plugins/bootstrap-notify.js"></script>
    <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="../assets/js/now-ui-dashboard.js?v=1.0.1"></script>
    <!-- Now Ui Dashboard DEMO methods, don't include it in your project! -->
    <script src="../assets/demo/demo.js?v=1.0.0"></script>
    <script src="test.js"></script>
    <script>
        $(document).ready(function() {
            <?if(($license - strtotime(date('d-m-Y'))) < 604800) {?>
                demo.showNotification('bottom','right', 'Срок действия лицензии истекает <?=$d->format('d.m.Y')?>.<br>Продлить лицензию можно <a style="text-decoration: none; color: #214268;" href="">здесь</a>', 'danger', 60000)
            <?}?>
            //demo.initDashboardPageCharts();
        });
    </script>
    <script>
        let fields = document.querySelectorAll('.field__file');
        Array.prototype.forEach.call(fields, function (input) {
        let label = input.nextElementSibling,
            labelVal = label.querySelector('.field__file-fake').innerText;
    
        input.addEventListener('change', function (e) {
            let countFiles = '';
            if (this.files && this.files.length >= 1)
            countFiles = this.files.length;
    
            if (countFiles)
            label.querySelector('.field__file-fake').innerText = 'Ключ выбран';
            else
            label.querySelector('.field__file-fake').innerText = labelVal;
        });
        });
    </script>

    </html>
<? } ?>