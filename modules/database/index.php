<?
namespace RoBot\Modules;

require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
use Krugozor\Database\Mysql as MySQL;

class Database
{
    private $db;
    public function __construct()
    {
        $this->db = MySql::create('37.140.192.137', 'u1144894_autolab', '7W3j9S6t')
            ->setDatabaseName('u1144894_autolab')
            ->setCharset('utf8');
    }
    public function AddAuth($auth)
    {
        $dbResult = $this->db->query("SELECT COUNT(0) FROM auth WHERE `domain` = '?s';", $auth['domain'])->fetchAssoc();
        if($dbResult['COUNT(0)'] == 0)
        {
            $arResult = $this->db->query(
                "INSERT INTO `auth` (`domain`, `access_token`, `expires_in`, `application_token`, `refresh_token`, `client_endpoint`) VALUES ('?s', '?s', '?s', '?s', '?s', '?s');",
                $auth['domain'],
                $auth['access_token'],
                $auth['expires_in'],
                $auth['application_token'],
                $auth['refresh_token'],
                $auth['client_endpoint'],
            );
        }
        else
        {
            $arResult = $this->db->query(
                "UPDATE `auth` SET `access_token` = '?s', `expires_in` = '?s', `application_token` = '?s', `refresh_token` = '?s', `client_endpoint` = '?s' WHERE (`domain` = '?s');",
                $auth['access_token'],
                $auth['expires_in'],
                $auth['application_token'],
                $auth['refresh_token'],
                $auth['client_endpoint'],
                $auth['domain'],
            );
        }
        return $arResult;
    }
    public function GetAuth()
    {
        
    }

    public function AddSettings($domain)
    {
        $dbResult = $this->db->query("SELECT COUNT(0) FROM settings WHERE `domain` = '?s';", $domain)->fetchAssoc();
        if($dbResult['COUNT(0)'] == 0)
        {
            $arResult = $this->db->query(
                "INSERT INTO `settings` (`domain`) VALUES ('?s');",
                $domain,
            );
        }
        $dbResult = $this->db->query("SELECT COUNT(0) FROM license WHERE `domain` = '?s';", $domain)->fetchAssoc();
        if($dbResult['COUNT(0)'] == 0)
        {
            $arResult = $this->db->query(
                "INSERT INTO `license` (`domain`, `start`, `end`) VALUES ('?s', '?s', '?s');",
                $domain,
                strtotime(date('d-m-Y')),
                (strtotime(date('d-m-Y')) + (86400*3)),
            );
        }
        return $arResult;
    }
    public function GetLicense($domain)
    {
        $dbResult = $this->db->query("SELECT end FROM license WHERE `domain` = '?s';", $domain)->fetchAssoc();
        return $dbResult['end'];
    }

    public function GetSettings($domain)
    {
        $dbResult = $this->db->query("SELECT * FROM settings WHERE `domain` = '?s';", $domain)->fetchAssoc();
        return $dbResult;
    }
    public function SaveSettings($domain, $settings)
    {
        $arResult = $this->db->query(
            "UPDATE `settings` SET `hitext` = '?s', `errortext` = '?s', `mode` = '?s' WHERE (`domain` = '?s');",
            $settings['hitext'],
            $settings['errortext'],
            $settings['mode'],
            $domain,
        );
        return $arResult;
    }

    public function AddDialogflowKey($domain, $key, $name)
    {
        $arResult = $this->db->query("UPDATE settings SET `aikey` = '?s', `ainame` = '?s' WHERE (`domain` = '?s');",
            $key,
            $name,
            $domain
        );
        return $arResult;
    }
}
?>