<?php
/**
 * @category Qsolutions
 * @package Q_Chart
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Chart_Block_Adminhtml_Shipping_Tab_Graph
    extends Q_Chart_Block_Adminhtml_Shipping_Shipping
{
    /**
     * @return Q_Chart_Helper_Adminhtml_Data
     */
    public function getDiagramHelper()
    {
        return Mage::helper('q_chart/adminhtml_data');
    }

    /**
     * @return int
     */
    private function getAddedShippingQty()
    {
        $shippingNecessaryData = $this->getNecessaryData();
        $allShipping = 0;

        foreach ($shippingNecessaryData as $shippingData) {
            $allShipping += $shippingData['shipping_qty'];
        }

        return $allShipping;
    }

    /**
     * @return Varien_Data_Collection
     * @throws Exception
     */
    protected function getDataAdjustedToShow()
    {
        $shippingNecessaryData = $this->getNecessaryData();
        $allShipping = $this->getAddedShippingQty();

        $chartData = new Varien_Data_Collection();

        foreach ($shippingNecessaryData as $key => $shippingData) {
            $shippingInPercent = round(($shippingData['shipping_qty']/$allShipping)*100, 2);

            $row = new Varien_Object();

            $row->setShippingMethodCode($key);
            if(!empty($shippingData['shipping_title'])) {
                $row->setShippingMethodTitle($shippingData['shipping_title']);
            } elseif (empty($shippingData['shipping_title']) && !empty($key)) {
                $row->setShippingMethodTitle($key);
            } else {
                $row->setShippingMethodTitle('no shipping info');
            }
            $row->setShippingQtyInPeriod($shippingData['shipping_qty']);
            $row->setOrdersAmountInPeriod($shippingData['orders_amount']);
            $row->setShippingMethodUsesInPercent($shippingInPercent);

            $chartData->addItem($row);
        }

        return $chartData;
    }
}