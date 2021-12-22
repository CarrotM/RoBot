<?

namespace RoBot\Modules\System;

class Log
{
    public static function print($domain, $data, $title = "DEBUG")
    {
        $date = date("d-m-Y");
        $path = __DIR__ . "/../../Log/{$domain}/{$date}/";

        if (!file_exists($path)) {
            @mkdir($path, 0777, true);
        }

        $path .= $title;
        $log = "\n----------------------------------------------------------------\n";
        $log .= "-----------------------Начало логирования-----------------------\n";
        $log .= "Время: " . date("Y.m.d G:i:s") . "\n";
        $log .= "Описание: " . (strlen($title) > 0 ? $title : 'DEBUG') . "\n";
        $log .= "Данные:" . "\n";
        $log .= print_r($data, 1);
        $log .= "\n-----------------------Конец логирования-----------------------\n";
        $log .= "---------------------------------------------------------------\n";
        file_put_contents($path . '.log', $log, FILE_APPEND);

        return true;
    }
}
class Statisctic
{
    public static function AddStat($domain, $type, $data = "Request")
    {
        $date = date("d-m-Y");
        $month = date("m-Y");
        $path = __DIR__ . "/../../Stat/{$domain}/{$type}/{$month}/";

        if (!file_exists($path)) {
            @mkdir($path, 0777, true);
        }
        $path .= $date . '.json';
        $arFile = [];
        if (file_exists($path)) {
            $arFile = json_decode(file_get_contents($path));
        }
        array_push($arFile, date("G"));

        file_put_contents($path, json_encode($arFile));

        return true;
    }
    public static function GetStat($domain, $type)
    {
        if ($type == "bot_request") {
            $date = date("d-m-Y");
            $month = date("m-Y");
            $path = __DIR__ . "/../../Stat/{$domain}/{$type}/{$month}/{$date}.json";
            $arResult = array(
                '0' => 0,
            );
            for ($i = 0; $i <= 23; $i++) {
                $arResult[$i] = 0;
            }
            if (file_exists($path)) {
                $arFile = json_decode(file_get_contents($path));
                foreach ($arFile as $arr) {
                    for ($i = 0; $i <= 23; $i++) {
                        if ($arr == $i) $arResult[$i] += 1;
                    }
                }
            }
            return $arResult;
        }
        if ($type == "bot_all") {
            $arResult = array();
            $start = strtotime(date('Y-m-01'));
            $finish = strtotime(date('Y-m-t'));

            $array = array();
            for ($i = $start; $i <= $finish; $i += 86400) {
                $list = explode('-', date('d-m-Y', $i));
                $array[] = implode('-', $list);
            }
            $montharr = array(
                '01-' . date("Y"),
                '02-' . date("Y"),
                '03-' . date("Y"),
                '04-' . date("Y"),
                '05-' . date("Y"),
                '06-' . date("Y"),
                '07-' . date("Y"),
                '08-' . date("Y"),
                '09-' . date("Y"),
                '10-' . date("Y"),
                '11-' . date("Y"),
                '12-' . date("Y"),
            );
            foreach ($montharr as $month) {
                $arResult[$month] = 0;
            }
            foreach ($montharr as $month) {
                foreach ($array as $arr) {
                    $path = __DIR__ . "/../../Stat/{$domain}/bot_request/{$month}/{$arr}.json";
                    if (file_exists($path)) {
                        $arFile = json_decode(file_get_contents($path));
                        foreach ($arFile as $arr) {
                            for ($i = 0; $i <= 23; $i++) {
                                if ($arr == $i) $arResult[$month] += 1;
                            }
                        }
                    }
                }
            }
            return $arResult;
        }
    }
}
