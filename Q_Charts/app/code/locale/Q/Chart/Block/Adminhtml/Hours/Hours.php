<?php
/**
 * @category Qsolutions
 * @package Q_Chart
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Chart_Block_Adminhtml_Hours_Hours
    extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * @var
     */
    private $_from;
    /**
     * @var
     */
    private $_to;
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
        $this->setFrom(date('Y-m-01'));
        $this->setTo(date('Y-m-d'));
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
     * @return $this
     */
    private function adjustDate()
    {
        $this->setFrom(date('Y-m-d 00:00:00', strtotime($this->_from)));
        $this->setTo(date('Y-m-d 23:59:59', strtotime($this->_to)));

        return $this;
    }

    /**
     * @return Mage_Sales_Model_Resource_Order_Item_Collection
     */
    protected function getOrdersCollectionGroupInHours()
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
            ->columns('SUBSTR(main_table.created_at, 12, 2) as hour_of_selling')
            ->join( array('orders'=> 'sales_flat_order'), 'orders.entity_id = main_table.order_id', array('orders.status'))
            ->where('orders.status = ?', Mage_Sales_Model_Order::STATE_COMPLETE)
            ->group('SUBSTR(main_table.created_at, 12, 2)')
        ;

        return $orders;
    }
}