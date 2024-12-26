<?php
/**
 * Created by PhpStorm.
 * User: DTSMART
 * Date: 1/21/2019
 * Time: 4:00 PM
 */

namespace app\api\modules\v1\test\behaviors;

use Yii;
use yii\base\ActionFilter;
use yii\web\UnauthorizedHttpException;

/**
 * Class AuthBehavior
 * @package app\modules\coreapi\behaviors
 */
class AuthBehavior extends ActionFilter
{
    /**
     * @var string
     */
    public $validKey;

    /**
     * @var string
     */
    public $authParam = 'token';

    /**
     * @inheritdoc
     * @throws UnauthorizedHttpException
     */
    public function beforeAction($action)
    {
        $key = Yii::$app->request->get($this->authParam);

        if ($key !== $this->validKey) {
            throw new UnauthorizedHttpException('Your request was made with invalid credentials.');
        }
        return true;
    }

}