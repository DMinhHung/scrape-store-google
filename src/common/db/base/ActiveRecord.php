<?php
/**
 * Smart Digitech LLC (c) 2020, All right reverse
 * GCI2020 Project
 */

namespace app\common\db\base;

use Yii;
use yii\db\ActiveRecord as BaseActiveRecord;

/**
 *Active Record class it support base soft delete, timestamp attribute...
 * Who implements it must be have common columns for support features.
 *
 *
 * @author Nguyen Xuan Tan <tannx@dtsmart.vn>
 * @since 2.0.0
 */
class ActiveRecord extends BaseActiveRecord
{

}