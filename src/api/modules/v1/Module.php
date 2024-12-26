<?php
/**
 * Smart Digitech LLC (c) 2019, All right reverse
 * gci_clouds Project
 */

namespace app\api\modules\v1;


use yii\filters\Cors;

/**
 * Class Module.php
 *
 * @author  Hung Dao <hungdao@dtsmart.vn>
 * * @package app\api\v1\report
 * * @version 1.0.0
 */
class Module extends \yii\base\Module
{
    /**
     * init
     * @author Binh Nguyen <binhnt@dtsmart.vn>
     */
    public function init()
    {
        parent::init();
        $this->modules = [
            'test' => [
                'class' => \app\api\modules\v1\test\Module::class,
            ],
            'report' => [
                'class' => \app\api\modules\v1\report\Module::class,
            ]
        ];
    }
}