<?php
/**
 * @category Qsolutions
 * @package Q_Chart
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Chart_Block_Adminhtml_Dashboard_Tab_Orders
    extends Q_Chart_Block_Adminhtml_Dashboard_Graph
{
    /**
     *
     */
    public function _construct()
    {
        $this->setTemplate('q/chart/dashboard/orders/diagram.phtml');
        parent::_construct();
    }

    /**
     * @return Q_Chart_Helper_Adminhtml_Data
     */
    public function getDiagramHelper()
    {
        return Mage::helper('q_chart/adminhtml_data');
    }

    /**
     * @return Varien_Data_Collection
     * @throws Exception
     */
    protected function getProcessedData()
    {
        $itemsCollection = $this->getOrderItemsCollection();
        $chartData = new Varien_Data_Collection();

        foreach ($itemsCollection as $item) {
            $date = new DateTime($item->getCreatedAt());
            $row = new Varien_Object();
            $row->setDate($date->format($this->adjustDateToViewInChart()));
            $row->setOrdersQty(intval($item->getCountedItems()));

            $chartData->addItem($row);
        }

        return $chartData;
    }

    /**
     * @return $this|string
     */
    private function adjustDateToViewInChart()
    {
        if ($this->_range === '24h') {
            return 'H:00';
        }

        if ($this->_range === '7d' || $this->_range === '1m' || $this->_range === 'custom') {
            return 'M d, Y';
        }

        if ($this->_range === '1y' || $this->_range === '2y') {
            return 'Y-m';
        }

        return $this;
    }
}