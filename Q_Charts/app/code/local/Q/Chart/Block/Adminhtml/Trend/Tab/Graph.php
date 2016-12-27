<?php
/**
 * @category Qsolutions
 * @package Q_Chart
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Chart_Block_Adminhtml_Trend_Tab_Graph
    extends Q_Chart_Block_Adminhtml_Trend_Trend
{
    /**
     * @return array
     */
    protected function getDataForChart()
    {
        $orderItemsCollection = $this->getOrderItemCollection();

        $collectedTrend = array();
        $collectedDate = array();

        foreach ($orderItemsCollection as $countedOrders) {
            $trendCollection = $countedOrders->getTrendCollection();
            foreach ($trendCollection as $trend) {
                $createdDate = date('Y-m-d', strtotime($trend->getCreatedAt()));
                if (!in_array($createdDate, $collectedDate)) {
                    $collectedDate[] = $createdDate;
                }
                $collectedTrend[$trend->getSku()][] = array(
                    'name'          => $trend->getName(),
                    'created_at'    => date('Y-m-d', strtotime($trend->getCreatedAt())),
                    'counted_items' => $trend->getCountedItems(),
                );
            }
        }
        $trendCollection = new Varien_Object();
        $trendCollection->setDate($collectedDate);
        $trendCollection->setProductInfo($collectedTrend);

        return $trendCollection;
    }

    /**
     * @return array
     */
    protected function getMultipleLineTrend()
    {
        $chartData = [];
        $dates= $this->getDataForChart()->getDate();
        $productsInfo = $this->getDataForChart()->getProductInfo();

        foreach ($productsInfo as $key => $products) {
            foreach ($dates as $date) {
                $dateAdjusted = new DateTime($date);
                $dateReadyToChart = $dateAdjusted->format($this->adjustDateToViewInChart($date));
                if (!array_key_exists($dateReadyToChart, $chartData)) {
                    $chartData[$dateReadyToChart] = [];
                }
                foreach ($products as $product) {
                    if ($date === $product['created_at']) {
                        $chartData[$dateReadyToChart][$key] = $product['counted_items'];
                    } elseif (!array_key_exists($key, $chartData[$dateReadyToChart])){
                        $chartData[$dateReadyToChart][$key] = 0;
                    }
                }
            }
        }

        return $chartData;
    }

    /**
     * @return array
     */
    protected function getAxisName()
    {
        $productsInfo = $this->getDataForChart()->getProductInfo();
        $productsSkus = array_keys($productsInfo);

        return $productsSkus;
    }

    /**
     * @return Q_Chart_Helper_Adminhtml_Data
     */
    public function getDiagramHelper()
    {
        return Mage::helper('q_chart/adminhtml_data');
    }

    /**
     * @return string
     */
    private function adjustDateToViewInChart()
    {
            if ($this->_period === 'day') {
            return 'M d, Y';
        }

        if ($this->_period === 'month') {
            return 'M Y';
        }

        if ($this->_period === 'year') {
            return 'Y';
        }
    }
}