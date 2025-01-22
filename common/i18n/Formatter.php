<?php

namespace common\i18n;

/**
 * Class Formatter
 *
 * @package common\i18n
 */
class Formatter extends \yii\i18n\Formatter
{
    public function asStudentStatus($status)
    {
        if ($status == \common\models\Student::STATUS_ACTIVE) {
            return \yii\bootstrap5\Html::tag('span', 'Completed', ['class' => 'badge badge-success']);
        } else if ($status == \common\models\Student::STATUS_INACTIVE) {
            return \yii\bootstrap5\Html::tag('span', 'Paid', ['class' => 'badge badge-primary']);
        } else {
            return \yii\bootstrap5\Html::tag('span', 'Failured', ['class' => 'badge badge-danger']);
        }
    }
}