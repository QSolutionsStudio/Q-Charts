<?php
/**
 * @category Qsolutions
 * @package Q_Admin
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Admin_Block_Adminhtml_Lifetime_Lifetime
    extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * @return Q_Admin_Helper_Orders
     */
    protected function getFirstSalesDate()
    {
        $orders = Mage::helper('q_admin/orders')
            ->reset()
            ->setColumns(array('sfo.created_at'))
            ->getSalesFlatOrderWithJoins()
        ;
        /* @var $orders Q_Admin_Helper_Orders */

        if (!empty($orders)) {
            return reset($orders);
        } else {
            return time();
        }
    }
}