<?php
/**
 * @category Qsolutions
 * @package Q_Admin
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Admin_Block_Adminhtml_Hours_Tab_Graph
    extends Q_Admin_Block_Adminhtml_Hours_Hours
{
    /**
     * @return Q_Admin_Helper_Adminhtml_Data
     */
    public function getDiagramHelper()
    {
        return Mage::helper('q_admin/adminhtml_data');
    }


    /**
     * @return Varien_Data_Collection
     * @throws Exception
     */
    protected function getCollectionPrepareToShow()
    {
        $ordersCollection = $this->getOrdersCollectionGroupInHours();

        $hoursCollection = new Varien_Data_Collection();
        for ($i = 0; $i <= 23; $i++) {
            $hour = new DateTime(date($i . ':00'));
            $hourLabel = $hour->format('H:00');
            $hourAdjustedToShow = $hour->format('H');

            foreach ($ordersCollection as $order) {
                if ($order->getHourOfSelling() === $hourAdjustedToShow) {
                    $row = new Varien_Object();
                    $row->setItemsQty(intval($order->getCountedItems()));
                    $row->setTotalAmount(intval($order->getTotalPayments()));
                    $row->setHourLabel($hourLabel);
                    $row->setHourAdjustedToShow($hourAdjustedToShow);

                    $hoursCollection->addItem($row);
                    continue;
                }
            }
        }

        return $hoursCollection;
    }
}