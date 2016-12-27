<?php
/**
 * @category Qsolutions
 * @package Q_Chart
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Chart_Block_Adminhtml_Trend_Tab_Table
    extends Q_Chart_Block_Adminhtml_Trend_Trend
{
    /**
     * @return Varien_Data_Collection
     * @throws Exception
     */
    protected function getTableData()
    {
        $orderItemsCollection = $this->getOrderItemCollection();

        $tableData = new Varien_Data_Collection();

        foreach ($orderItemsCollection as $countedOrders) {
            $trendCollection = $countedOrders->getTrendCollection();
            foreach ($trendCollection as $trend) {
                $row = new Varien_Object();
                if ($this->_period === 'day') {
                    $row->setPeriod(substr($trend->getCreatedAt(), 0, 10));
                } elseif ($this->_period === 'month') {
                    $row->setPeriod(substr($trend->getCreatedAt(), 0, 7));
                } elseif ($this->_period === 'year') {
                    $row->setPeriod(substr($trend->getCreatedAt(), 0, 4));
                };
                $row->setName($trend->getName());
                $row->setSku($trend->getSku());
                $row->setProductId($trend->getProductId());
                $row->setCountedQty($trend->getCountedItems());
                $row->setTotalPayment($trend->getTotalPayments());

                $tableData->addItem($row);
            }
        }

        return $tableData;
    }
}