<?php
/**
 * Smart Digitech LLC (c) 2020, All right reverse
 * GCI2020 Project
 */

namespace app\common\db;

use app\common\db\base\ActiveQuery;

/**
 * Class BusinessQuery is the BusinessQuery class for [[Business]].
 *
 * @see Queue
 *
 * @author dtsmart.vn
 * @since 1.0.0
 */
class QueueQuery extends ActiveQuery
{

    /**
     * @inheritdoc
     * @return Queue[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Queue|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
