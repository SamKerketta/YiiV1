<?php

namespace app\commands;

use app\models\TablLog;
use app\models\User;
use yii\console\Controller;
use yii\helpers\Console;

class MyCommandController extends Controller
{
    /**
     * This action will be executed when the "hello" command is run.
     * 
     * @param string $name The name to greet.
     */
    public function actionUser($pass = 'Password')
    {
        # making a log of the request 
        $log = new TablLog();

        $userDetails = [
            "name" => randomName(7),
            "email" => randomName(8) . "@gmail.com",
            "password" => base64_encode($pass),
        ];
        $log->saveLogs($userDetails);

        $this->stdout("Random user named : , {$userDetails['name']}!\n", Console::FG_GREEN);
    }
}
