<?php
/**
 * @category Qsolutions
 * @package Q_Chart
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */

class Q_Chart_Block_Adminhtml_Dashboard_Tab_Totals
    extends Q_Chart_Block_Adminhtml_Dashboard_Graph
{
    /**
     * @var
     */
    protected $_revenue;
    /**
     * @var
     */
    protected $_tax;
    /**
     * @var
     */
    protected $_shipping;
    /**
     * @var
     */
    protected $_qty;

    /**
     *
     */
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('qsolutions/dashboard/table.phtml');
        $this->setCurrency();
    }

    /**
     * @return $this
     */
    protected function setTableData()
    {
        $this->_revenue  = 0;
        $this->_tax      = 0;
        $this->_shipping = 0;
        $this->_qty      = 0;

        $ordersItemCollection = $this->getOrderItemsCollection();

        foreach ($ordersItemCollection as $item) {
            $this->_revenue  += $item->getTotalPayments();
            $this->_tax      += $item->getTotalTaxAmount();
            $this->_shipping += $item->getTotalShippingAmount();
            $this->_qty      += $item->getCountedItems();
        }

        return $this;
    }

    /**
     * @return Varien_Object
     */
    protected function prepareDataToTable()
    {
        $this->setTableData();

        $row = new Varien_Object();
        $row->setRevenue($this->_revenue);
        $row->setTax($this->_tax);
        $row->setShipping($this->_shipping);
        $row->setQty($this->_qty);

        return $row;
    }
}