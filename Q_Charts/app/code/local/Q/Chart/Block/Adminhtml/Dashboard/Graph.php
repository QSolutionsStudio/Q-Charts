<?php
/**
 * @category Qsolutions
 * @package Q_Chart
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Chart_Block_Adminhtml_Dashboard_Graph
    extends Mage_Adminhtml_Block_Dashboard_Abstract
{
    /**
     * @var
     */
    protected $_from;
    /**
     * @var
     */
    protected $_to;
    /**
     * @var
     */
    protected $_range;
    /**
     * @var
     */
    private $_storesIds;

    /**
     *
     */
    public function _construct()
    {
        parent::_construct();
        $this->setRange('24h');
        $this->_storesIds = array_keys(Mage::app()->getStores());
    }

    /**
     * @param string $from
     * @return $this
     */
    public function setFrom($from)
    {
        $this->_from = $from;

        return $this;
    }

    /**
     * @param string $to
     * @return $this
     */
    public function setTo($to)
    {
        $this->_to = $to;

        return $this;
    }

    /**
     * @param string $range
     * @return $this
     */
    public function setRange($range)
    {
        $this->_range = $range;

        return $this;
    }

    /**
     * @return $this
     */
    private function adjustDate()
    {
        if ($this->_range === '24h') {
            $this->setFrom(date('Y-m-d 00:00:00'));
            $this->setTo(now());
        } elseif ($this->_range === '7d') {
            $this->setFrom(date('Y-m-d 00:00:00', strtotime("-1 week")));
            $this->setTo(now());
        } elseif ($this->_range === '1m') {
            $this->setFrom(date('Y-m-01 00:00:00'));
            $this->setTo(now());
        } elseif ($this->_range === '1y') {
            $this->setFrom(date('Y-01-01 00:00:00'));
            $this->setTo(now());
        } elseif ($this->_range === '2y') {
            $this->setFrom(date('Y-m-d 00:00:00', strtotime("-2 year")));
            $this->setTo(now());
        } elseif ($this->_range === 'custom') {
            $this->setFrom(date('Y-m-d 00:00:00', strtotime($this->_from)));
            $this->setTo(date('Y-m-d 23:59:59', strtotime($this->_to)));
        }

        return $this;
    }

    /**
     * @return Mage_Sales_Model_Resource_Order_Item_Collection
     */
    protected function getOrderItemsCollection()
    {
        $this->adjustDate();
        $period = array(
            'from'  => $this->_from,
            'to'    => $this->_to,
        );

        $orders =  Mage::getResourceModel('sales/order_item_collection')
            ->addFilterToMap('created_at', 'main_table.created_at')
            ->addFilterToMap('store_id', 'main_table.store_id')
            ->addFieldToFilter('created_at', $period)
            ->addFieldToFilter('parent_item_id', array('null' => true))
            ->addFieldToFilter('store_id', $this->_storesIds)
        ;

        /* @var $orders Mage_Sales_Model_Resource_Order_Item_Collection */
        $orders->getSelect()
            ->columns('SUM(main_table.qty_ordered) as counted_items')
            ->columns('SUM(main_table.row_total) as total_payments')
            ->columns('SUM(main_table.tax_amount) as total_tax_amount')
            ->join(array('orders'=> 'sales_flat_order'), 'orders.entity_id = main_table.order_id', array('orders.status', 'orders.shipping_amount'))
            ->columns('SUM(orders.shipping_amount) as total_shipping_amount')
            ->where('orders.status = ?', Mage_Sales_Model_Order::STATE_COMPLETE)
            ->group(sprintf('SUBSTR(main_table.created_at, 1, %d)', $this->getLengthBasedOnRange()))
        ;

        return $orders;
    }

    /**
     * @return string
     */
    private function getLengthBasedOnRange()
    {
        $range = $this->_range;

        if ($range === '24h') {
            return '13';
        }

        if ($range === '7d' || $range === '1m' || $range === 'custom') {
            return '10';
        }

        if ($range === '1y' || $range === '2y') {
            return '7';
        }

        return '16';
    }
}