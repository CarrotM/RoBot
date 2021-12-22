<?
require_once __DIR__."/modules/bitrix/install/index.php";
use RoBot\Modules\Bitrix\Install;

$_REQUEST['auth'] = array(
    'access_token' => htmlspecialchars($_REQUEST['AUTH_ID']),
	'expires_in' => htmlspecialchars($_REQUEST['AUTH_EXPIRES']),
	'application_token' => htmlspecialchars($_REQUEST['APP_SID']),
	'refresh_token' => htmlspecialchars($_REQUEST['REFRESH_ID']),
	'domain' => htmlspecialchars($_REQUEST['DOMAIN']),
	'client_endpoint' => 'https://' . htmlspecialchars($_REQUEST['DOMAIN']) . '/rest/',
);
$arResult = Install::Install($_REQUEST['auth']);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="./Logo50Transperent.png">
    <link rel="icon" type="image/png" href="./Logo50Transperent.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Установка приложения</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <!-- CSS Files -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/now-ui-dashboard.css?v=1.0.1" rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
	<script src="//api.bitrix24.com/api/v1/"></script>
		<?php if($arResult['installed'] == 1):?>
			<script>
				BX24.init(function(){
					BX24.installFinish();
				});
			</script>
		<?php endif;?>
</head>

<body class="">
    <div class="wrapper ">
        <div class="main-panel" style="width: 100%;">
            <div class="panel-header panel-header-sm">
            </div>
            <div class="content">
                <div class="row">
					<?php if($arResult['installed'] == 1):?>
						<div class="col-md-8 ml-auto mr-auto">
							<div class="card card-upgrade">
								<div class="card-header text-center">
									<h4 class="card-title">Успешно установлено</h3>
								</div>
								<div class="card-body text-center">
									<h1 style="font-size: 150px; color: #0f0;"><i class="now-ui-icons ui-1_check"></i></h1>
								</div>
							</div>
						</div>
					<?php else:?>
						<div class="col-md-8 ml-auto mr-auto">
							<div class="card card-upgrade">
								<div class="card-header text-center">
									<h4 class="card-title">Ошибка установки</h3>
										<p class="card-category">ОБРАТИТЕСЬ В ТЕХНИЧЕСКУЮ ПОДДЕРЖКУ ЗА ПОМОЩЬЮ</p>
								</div>
								<div class="card-body text-center">
									<h1 style="font-size: 150px; color: #f00;"><i class="now-ui-icons ui-1_simple-remove"></i></h1>
								</div>
							</div>
						</div>
					<?php endif;?>
                </div>
            </div>
        </div>
    </div>
</body>
<!--   Core JS Files   -->
<script src="../assets/js/core/bootstrap.min.js"></script>
</html>
