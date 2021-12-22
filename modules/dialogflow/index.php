<?
namespace RoBot\Modules\Dialogflow;

require_once __DIR__.'/../../vendor/autoload.php';
use Google\Cloud\Dialogflow\V2\SessionsClient;
use Google\Cloud\Dialogflow\V2\TextInput;
use Google\Cloud\Dialogflow\V2\QueryInput;
class Chat
{
    public static function GetMessage($text, $sessionId, $projectId, $projectKey)
    {
        $languageCode = 'ru-RU';
        // new session
        $test = array('credentials' => __DIR__."/../../Dialogflow/{$projectKey}.json");
        $sessionsClient = new SessionsClient($test);
        $session = $sessionsClient->sessionName($projectId, $sessionId ?: uniqid());

        // create text input
        $textInput = new TextInput();
        $textInput->setText($text);
        $textInput->setLanguageCode($languageCode);

        // create query input
        $queryInput = new QueryInput();
        $queryInput->setText($textInput);

        // get response and relevant info
        $response = $sessionsClient->detectIntent($session, $queryInput);
        $queryResult = $response->getQueryResult();
        $queryText = $queryResult->getQueryText();
        $intent = $queryResult->getIntent();
        $displayName = $intent->getDisplayName();
        $confidence = $queryResult->getIntentDetectionConfidence();
        $fulfilmentText = $queryResult->getFulfillmentText();
        $fields = $queryResult->getParameters()->getFields();
        $parameters = [];
        foreach($fields as $key => $field) {
            if(strlen(static::Params($field)) > 0)$parameters[$key] = static::Params($field);
            $_parameters[$key] = static::Params($field);
        }

        $sessionsClient->close();
        return array(
            "Intent" => $displayName,
            "Text" => $fulfilmentText,
            "Params" => $parameters
        );
    }

    private static function Params($field) 
    {
        $kind = $field->getKind();
        if ($kind == "string_value")
            return $field->getStringValue();
        else if ($kind == "number_value")
            return $field->getNumberValue();
        else if ($kind == "bool_value")
            return $field->getBoolValue();
        else if ($kind == "null_value")
            return $field->getNullValue();
        else if ($kind == "list_value") {
            $list_values = $field->getListValue()->getValues();
            $values = [];
            foreach($list_values as $list_value)
                $values[] =get_field_value($list_value);
            return $values;    
        }
        else if ($kind == "struct_value")
            return $field->getStructValue();
    }
}
?>