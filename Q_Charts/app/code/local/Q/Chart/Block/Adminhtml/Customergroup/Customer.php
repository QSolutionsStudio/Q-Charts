<?php
/**
 * @category Qsolutions
 * @package Q_Chart
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Chart_Block_Adminhtml_Customergroup_Customer
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
     * @return Mage_Customer_Model_Group
     */
    private function getCustomerGroupsCollection()
    {
        return Mage::getModel('customer/group')->getCollection();
    }

    /**
     * @param string $customerGroup
     * @return Q_Chart_Helper_Orders
     */
    private function getSalesOrderCollectionLimitedByPeriod($customerGroup)
    {
        $this->adjustDate();
        $period = array(
            'from'  => $this->_from,
            'to'    => $this->_to,
        );

        /* @var $orders Q_Chart_Helper_Orders */
        $orders = Mage::helper('q_chart/orders')
            ->reset()
            ->setPeriod($period)
            ->setColumns(array('COUNT(*) as counted_customer'))
            ->setWhere(sprintf('customer_group_id = %s', $customerGroup))
            ->getSalesFlatOrderWithJoins()
        ;

        return $orders;
    }

    /**
     * @return Varien_Data_Collection
     * @throws Exception
     */
    protected function getSalesQtyByCustomerGroup()
    {
        $customerGroups = $this->getCustomerGroupsCollection();

        $customerGroupSalesLimitedByPeriod = new Varien_Data_Collection();

        foreach ($customerGroups as $group) {
            $row = new Varien_Object();

            $ordersCollection = $this->getSalesOrderCollectionLimitedByPeriod($group->getCustomerGroupId());

            $countedCustomers = $ordersCollection[0]['counted_customer'];

            $row->setCustomerGroupId($group->getCustomerGroupId());
            $row->setCustomerGroupCode($group->getCustomerGroupCode());
            $row->setCustomerGroupSales($countedCustomers);

            $customerGroupSalesLimitedByPeriod->addItem($row);
        }

        return $customerGroupSalesLimitedByPeriod;
    }
}