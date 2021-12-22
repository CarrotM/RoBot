<?

namespace RoBot\Modules\Bitrix;

require_once __DIR__ . '/../../database/index.php';
require_once __DIR__ . '/../crest.php';

use RoBot\Modules\Database;
use RoBot\Modules\Bitrix\CRest;

class Install
{
    public static function Install($auth)
    {
        $db = new Database();
        $arResult['AddDB'] = $db->AddAuth($auth);
        $arResult['AddBot'] = static::RegisterBot($auth);
        $arResult['AddSettings'] = $db->AddSettings($auth['domain']);
        $arResult['installed'] = true;
        return $arResult;
    }
    private static function RegisterBot($auth)
    {
        $crest = new CRest();
        $handlerBackUrl = "https://itbrains.ru/index.php";

        return $crest::restCommand(
            'imbot.register',
            array(
                'CODE' => 'ITBrains_RoBot',
                'TYPE' => 'O',
                'EVENT_MESSAGE_ADD' => $handlerBackUrl,
                'EVENT_WELCOME_MESSAGE' => $handlerBackUrl,
                'EVENT_BOT_DELETE' => $handlerBackUrl,
                'OPENLINE' => 'Y', // this flag only for Open Channel mode http://bitrix24.ru/~bot-itr
                'PROPERTIES' => array(
                    'NAME' => 'RoBot',
                    'COLOR' => 'GREEN',
                    'EMAIL' => 'admin@itbrains.ru',
                    'PERSONAL_BIRTHDAY' => '2020-01-01',
                    'WORK_POSITION' => 'RoBot - помощник менеджеров',
                    'PERSONAL_WWW' => 'https://itbrains.ru/',
                    'PERSONAL_GENDER' => 'M',
                    'PERSONAL_PHOTO' => base64_encode(file_get_contents(__DIR__.'/../../../Logo.png')),
                ),
            ),
            $auth
        );
    }
}
