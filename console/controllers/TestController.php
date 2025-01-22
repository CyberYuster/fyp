<?php

namespace console\controllers;

use yii\console\Controller;
use yii\console\ExitCode;

class TestController extends Controller
{
    public function actionIndex()
    {
        echo "Test command executed\n";
        return ExitCode::OK;
    }
}
