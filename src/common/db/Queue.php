<?php
/**
 * Smart Digitech LLC (c) 2020, All right reverse
 * GCI2020 Project
 */

namespace app\common\db;


use yii\helpers\ArrayHelper;

use app\common\db\base\Queue as BaseQueue;

/**
 * Class Business.
 *
 * @autho dtsmart.vn
 * @since 1.0.0
 */
class Queue extends BaseQueue
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            # custom behaviors
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            # custom validation rules
        ]);
    }

    /**
     * @inheritdoc
     * @return BusinessQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BusinessQuery(static::class);
    }
}
