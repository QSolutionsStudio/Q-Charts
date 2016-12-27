<?php
/**
 * @category Qsolutions
 * @package Q_Chart
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Chart_Helper_Orders
    extends Mage_Core_Helper_Abstract
{
    /**
     * @var
     */
    protected $_joinedTable;
    /**
     * @var
     */
    protected $_period;
    /**
     * @var
     */
    protected $_columns;
    /**
     * @var
     */
    protected $_where;
    /**
     * @var
     */
    protected $_group;

    /**
     * @return $this
     */
    public function reset()
    {
        $this->_joinedTable = null;
        $this->_period      = null;
        $this->_columns     = null;
        $this->_where       = null;
        $this->_group       = null;

        return $this;
    }

    /**
     * @param string $tableName
     * @return $this
     */
    public function setJoinedTable($tableName)
    {
        $this->_joinedTable = $tableName;

        return $this;
    }

    /**
     * @param string $period
     * @return $this
     */
    public function setPeriod($period)
    {
        $this->_period = $period;

        return $this;
    }

    /**
     * @param string $columns
     * @return $this
     */
    public function setColumns($columns)
    {
        $this->_columns = $columns;

        return $this;
    }

    /**
     * @param string $where
     * @return $this
     */
    public function setWhere($where)
    {
        $this->_where = $where;

        return $this;
    }

    /**
     * @param string $groupColumn
     * @return $this
     */
    public function setGroup($groupColumn)
    {
        $this->_group = $groupColumn;

        return $this;
    }

    /**
     * @return $this|string
     */
    protected function getAcronymOfJoinedTable()
    {
        if(!empty($this->_joinedTable)) {
            $words = explode('_', $this->_joinedTable);
            $acronym = '';
            foreach ($words as $letters) {
                $acronym .= $letters[0];
            }

            return $acronym;
        } else
        {
            return $this;
        }
    }

    /**
     * @return array
     */
    public function getSalesFlatOrderWithJoins()
    {
        $tableShortName = $this->getAcronymOfJoinedTable();

        $resource = Mage::getSingleton('core/resource');
        /* @var $resource Mage_Core_Model_Resource */

        $readConnection = $resource->getConnection('core_read');

        $ordersWithJoins = $readConnection->select()
            ->from(array('sfo' => $resource->getTableName('sales_flat_order')), array('entity_id'));
            if (!empty($this->_joinedTable)) {
                $ordersWithJoins->join([$tableShortName => $resource->getTableName($this->_joinedTable)], $tableShortName . '.entity_id = sfo.entity_id', array());
            };
            $ordersWithJoins->columns($this->_columns);

            if(!empty($this->_period)) {

                $ordersWithJoins->where("sfo.created_at >= ?", date('Y-m-d H:i:s', strtotime($this->_period['from'])))
                    ->where("sfo.created_at <= ?", date('Y-m-d H:i:s', strtotime($this->_period['to'])));
            }
            $ordersWithJoins->where('sfo.status = ?', Mage_Sales_Model_Order::STATE_COMPLETE)
        ;
        if (!empty($this->_where)) {
                $ordersWithJoins->where($this->_where);
            };
            if (!empty($this->_group)) {
                $ordersWithJoins->group($this->_group);
            };

        return $readConnection->fetchAll($ordersWithJoins);
    }
}