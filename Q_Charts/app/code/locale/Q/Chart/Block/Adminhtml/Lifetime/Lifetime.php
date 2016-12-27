<?php
/**
 * @category Qsolutions
 * @package Q_Chart
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Chart_Block_Adminhtml_Lifetime_Lifetime
    extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * @return Q_Chart_Helper_Orders
     */
    protected function getFirstSalesDate()
    {
        $orders = Mage::helper('q_chart/orders')
            ->reset()
            ->setColumns(array('sfo.created_at'))
            ->getSalesFlatOrderWithJoins()
        ;
        /* @var $orders Q_Chart_Helper_Orders */

        if (!empty($orders)) {
            return reset($orders);
        } else {
            return time();
        }
    }
}