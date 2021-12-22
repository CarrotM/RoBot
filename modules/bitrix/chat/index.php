<?
namespace RoBot\Modules\Bitrix;

require_once __DIR__.'/../../database/index.php';
require_once __DIR__.'/../crest.php';
require_once __DIR__.'/../../dialogflow/index.php';
require_once __DIR__.'/../../system/index.php';

use RoBot\Modules\System\Log;
use RoBot\Modules\System\Statisctic;
use RoBot\Modules\Database;
use RoBot\Modules\Bitrix\CRest;
use RoBot\Modules\Dialogflow\Chat as DFChat;

class Chat
{
    private $db;
    private $request;
    private $settings;

    public function __construct($request, $settings)
    {
        $this->db = new Database();
        $this->request = $request;
        $this->settings = $settings;
    }

    public function Message()
    {
        Statisctic::AddStat($this->request['DOMAIN'], 'bot_request');
        $message = null;
        if($this->settings['mode'] == 1)
        {
            $ai = DFChat::GetMessage($this->request['Chat']['MESSAGE'], $this->request['Chat']["DIALOG_ID"], $this->settings['aikey'], $this->settings['ainame']);
            Log::print($this->request['DOMAIN'], $ai);
            $message = $ai['Text'];
        }
        if($message != null)
        {
            CRest::restCommand(
                'imbot.message.add', 
                Array(
                    "DIALOG_ID" => $this->request['Chat']["DIALOG_ID"],
                    "MESSAGE" => (string)$message,
                ), 
                $this->request['Chat']['AUTH']);
        }
    }
}
?>