<?php

namespace RoBot\Modules\Dialogflow;
require_once __DIR__.'/../database/index.php';
use RoBot\Modules\Database;
class LoadKey
{
    public static function LoadFile($domain)
    {
        if (isset($_FILES['json'])) {
            $fileTmpName = $_FILES['json']['tmp_name'];
            $errorCode = $_FILES['json']['error'];
            if ($errorCode !== UPLOAD_ERR_OK || !is_uploaded_file($fileTmpName)) {
                $errorMessages = [
                    UPLOAD_ERR_INI_SIZE   => 'Размер файла превысил значение upload_max_filesize в конфигурации PHP.',
                    UPLOAD_ERR_FORM_SIZE  => 'Размер загружаемого файла превысил значение MAX_FILE_SIZE в HTML-форме.',
                    UPLOAD_ERR_PARTIAL    => 'Загружаемый файл был получен только частично.',
                    UPLOAD_ERR_NO_FILE    => 'Файл не был загружен.',
                    UPLOAD_ERR_NO_TMP_DIR => 'Отсутствует временная папка.',
                    UPLOAD_ERR_CANT_WRITE => 'Не удалось записать файл на диск.',
                    UPLOAD_ERR_EXTENSION  => 'PHP-расширение остановило загрузку файла.',
                ];
                $unknownMessage = 'При загрузке файла произошла неизвестная ошибка.';
                $outputMessage = isset($errorMessages[$errorCode]) ? $errorMessages[$errorCode] : $unknownMessage;
                die($outputMessage);
                return false;
            } else {
                $name = static::getRandomFileName($fileTmpName);
                if (!move_uploaded_file($fileTmpName, __DIR__.'/../../Dialogflow/' . $name . '.json')) {
                    return false;
                }
                $json = json_decode(file_get_contents(__DIR__.'/../../Dialogflow/' . $name . '.json'), true);
                $db = new Database();
                $db->AddDialogflowKey($domain, $json["project_id"], $name);
                return true;
            }
        };
    }


    private static function getRandomFileName($path)
    {
        $path = $path ? $path . '/' : '';
        do {
            $name = md5(microtime() . rand(0, 9999));
            $file = $path . $name;
        } while (file_exists($file));

        return $name;
    }
}
