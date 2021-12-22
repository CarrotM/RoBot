<?

namespace RoBot\Modules\Bitrix;

class CRest
{
    public static function restCommand($method, array $params = array(), array $auth = array(), $authRefresh = true)
    {
        $queryUrl = $auth["client_endpoint"] . $method;
        $queryData = http_build_query(array_merge($params, array("auth" => $auth["access_token"])));

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => 1,
            CURLOPT_URL => $queryUrl,
            CURLOPT_POSTFIELDS => $queryData,
        ));

        $result = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($result, 1);

        if ($authRefresh && isset($result['error']) && in_array($result['error'], array('expired_token', 'invalid_token'))) {
            $auth = static::restAuth($auth);
            if ($auth) {
                $result = static::restCommand($method, $params, $auth, false);
            }
        }

        return $result;
    }
    public static function restAuth($auth)
    {
        if(!isset($auth['refresh_token']))
            return false;

        $queryUrl = 'https://oauth.bitrix.info/oauth/token/';
        $queryData = http_build_query($queryParams = array(
            'grant_type' => 'refresh_token',
            'client_id' => 'local.61b224861e8bf9.35787360',
            'client_secret' => 'EKs2klTlsVHFPi85lYIPFy9uT6JFIZae7NHYcHyU7vISkiSeI9',
            'refresh_token' => $auth['refresh_token'],
        ));

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $queryUrl.'?'.$queryData,
        ));

        $result = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($result, 1);
        if (!isset($result['error']))
        {
            $appsConfig = Array();
            if (file_exists(__DIR__.'/config.php'))
                include(__DIR__.'/config.php');

            $result['application_token'] = $auth['application_token'];
            $appsConfig[$auth['application_token']]['AUTH'] = $result;
            //static::saveParams($appsConfig);
        }
        else
        {
            $result = false;
        }

        return $result;
    }

}
