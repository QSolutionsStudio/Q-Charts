<?php
/**
 * @category Qsolutions
 * @package Q_Admin
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Admin_Block_Adminhtml_Country_Sales
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
    private $storesIds;

    /**
     *
     */
    public function _construct()
    {
        parent::_construct();
        $this->setFrom(date('Y-m-01'));
        $this->setTo(date('Y-m-d'));
        $this->storesIds = array_keys(Mage::app()->getStores());
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
     * @return Mage_Sales_Model_Order
     */
    private function getOrdersWithAddressesCollectionLimitedByPeriod()
    {
        $this->adjustDate();
        $period = array(
            'from'  => $this->_from,
            'to'    => $this->_to,
        );

        $orders = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('created_at', $period)
            ->addFieldToFilter('store_id', $this->storesIds)
            ->addFieldToFilter('status', Mage_Sales_Model_Order::STATE_COMPLETE)
        ;
        /* @var $orders Mage_Sales_Model_Order */

        $orders->getSelect()
            ->columns('SUM(main_table.grand_total) as grand_total_by_country')
            ->columns('SUM(main_table.total_qty_ordered) as total_item_count_by_country')
            ->join(array('address'=> 'sales_flat_order_address'), 'address.entity_id = main_table.shipping_address_id', 'address.country_id')
            ->columns('COUNT(*) as sales_counted_by_country')
            ->group('address.country_id');

        return $orders;
    }

    /**
     * @return Varien_Data_Collection
     * @throws Exception
     */
    protected function getSalesByCountryCollection()
    {
        $ordersWithAddresses = $this->getOrdersWithAddressesCollectionLimitedByPeriod();

        $salesByCountry = new Varien_Data_Collection();

        foreach ($ordersWithAddresses as $orderAndAddress) {
            $countryId   = $orderAndAddress->getCountryId();
            $countryName = Mage::app()->getLocale()->getCountryTranslation($countryId);

            $row = new Varien_Object();
            $row->setCountryId($countryId);
            $row->setCountryName($countryName);
            $row->setOrdersQty($orderAndAddress->getSalesCountedByCountry());
            $row->setItemsQty(intval($orderAndAddress->getTotalItemCountByCountry()));
            $row->setGrandTotalByCountry($orderAndAddress->getGrandTotalByCountry());

            $salesByCountry->addItem($row);
        }

        return $salesByCountry;
    }
}