<?php
/**
 * @category Qsolutions
 * @package Q_Admin
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Admin_Block_Adminhtml_Hours_Tab_Table
    extends Q_Admin_Block_Adminhtml_Hours_Hours
{
    /**
     * @return Varien_Data_Collection
     * @throws Exception
     */
    protected function getCountedCollection()
    {
        $ordersCollection = $this->getOrdersCollectionGroupInHours();

        $dataToChart = new Varien_Data_Collection();

        for ($i = 0; $i <= 23; $i++) {
            $hour = new DateTime(date($i . ':00'));
            $hourAdjustedToCollection = $hour->format('H:00');

            $row = new Varien_Object();
            $row->setHoursValue($hourAdjustedToCollection);
            $row->setItemsQty('0');
            $row->setTotalAmount('0');

            foreach ($ordersCollection as $order) {
                $hourToCompere = $hour->format('H');

                if ($order->getHourOfSelling() === $hourToCompere) {
                    $row->setItemsQty(intval($order->getCountedItems()));
                    $row->setTotalAmount($order->getTotalPayments());
                    break;
                }
            }

            $dataToChart->addItem($row);
        }

        return $dataToChart;
    }
}