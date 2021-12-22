<?

namespace RoBot\Modules\Bitrix;

class Request
{
    public static function GetRequest()
    {
        $arResult = [];


        if (isset($_REQUEST['DOMAIN'])) $arResult['DOMAIN'] = $_REQUEST['DOMAIN'];
        else if (isset($_REQUEST['auth']['domain'])) $arResult['DOMAIN'] = $_REQUEST['auth']['domain'];
        else $arResult['DOMAIN'] = 'b24-hzomhx.bitrix24.ru';

        if($arResult['DOMAIN'] == 'b24-hzomhx.bitrix24.ru')
        {
            $arResult['TYPE'] = 'Interface';
            $arResult['Interface'] = array(
                    "LANG" => 'ru'
            );
        }
        else
        {
            if (isset($_REQUEST['PLACEMENT'])) $arResult['TYPE'] = "Interface";
            else $arResult['TYPE'] = "Chat";

            if ($arResult['TYPE'] == "Interface") {
                $arResult['Interface'] = array(
                    "LANG" => 'ru'
                );
            } else if ($arResult['TYPE'] == "Chat") {
                $arResult['Chat'] = array(
                    "FROM_USER_ID" => $_REQUEST['data']['PARAMS']['FROM_USER_ID'],
                    "DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
                    "MESSAGE" => $_REQUEST['data']['PARAMS']['MESSAGE'],
                    "AUTH" => $_REQUEST['auth']
                );
            }
        }

        return $arResult;
    }
}
