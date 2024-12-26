<?php
/**
 * Smart Digitech LLC (c) 2020, All right reverse
 * GCI2020 Project
 */

namespace app\common\db\base;

use yii\caching\DbQueryDependency;
use yii\db\Query;
use yii\db\ActiveQuery as BaseActiveQuery;

/**
 * Class ActiveQuery
 *
 * @see ActiveRecord
 *
 * @author Nguyen Xuan Tan <tannx@dtsmart.vn>
 * @since 2.0.0
 */
class ActiveQuery extends BaseActiveQuery
{
    /**
     * @var array|null
     */
    private $_tableNameAndAlias;

    /**
     * @inheritdoc
     */
    protected function getTableNameAndAlias()
    {
        if ($this->_tableNameAndAlias === null) {
            return $this->_tableNameAndAlias = parent::getTableNameAndAlias();
        } else {
            return $this->_tableNameAndAlias;
        }
    }

    /**
     * @return static
     */
    public function cacheByTime()
    {
        $dependency = static::createDbQueryDependency($this->getPrimaryTableName());
        $this->cache(0, $dependency);

        return $this;
    }

    /**
     * @param $table
     * @return DbQueryDependency
     */
    public static function createDbQueryDependency($table)
    {
        $query = (new Query)
            ->select(['MAX([[created_at]]) as MCA', 'MAX([[updated_at]]) as MUA', 'COUNT(*) as C'])
            ->from($table);

        return new DbQueryDependency([
            'query' => $query,
            'reusable' => true,
            'method' => 'all'
        ]);
    }

    public function createModels($rows)
    {
        return parent::createModels($rows);
    }
}