<?php
/**
 * @category Qsolutions
 * @package Q_Admin
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Admin_Block_Adminhtml_Shipping_Shipping
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
     * @param $from
     * @return $this
     */
    public function setFrom($from)
    {
        $this->_from = $from;

        return $this;
    }

    /**
     * @param $to
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
     * @return Q_Admin_Helper_Orders
     */
    private function getOrderCollection()
    {
        $this->adjustDate();
        $period = array(
            'from'  => $this->_from,
            'to'    => $this->_to,
        );

        $orders = Mage::helper('q_admin/orders')
            ->reset()
            ->setPeriod($period)
            ->setColumns(array('shipping_method', 'shipping_description', 'grand_total'))
            ->getSalesFlatOrderWithJoins()
        ;
        /* @var $orders Q_Admin_Helper_Orders */

        return $orders;
    }

    /**
     * @return array
     */
    protected function getNecessaryData()
    {
        $orders = $this->getOrderCollection();

        $shippingGroups = array();

        foreach ($orders as $order) {
            if(array_key_exists($order['shipping_method'], $shippingGroups)){
                $shippingGroups[$order['shipping_method']] = array(
                    'shipping_title' => $order['shipping_description'],
                    'shipping_qty'   => ++$shippingGroups[$order['shipping_method']]['shipping_qty'],
                    'orders_amount'  => $shippingGroups[$order['shipping_method']]['orders_amount'] + $order['grand_total'],
                );
            } else {
                $shippingGroups[$order['shipping_method']] = array(
                    'shipping_title' => $order['shipping_description'],
                    'shipping_qty'   => 1,
                    'orders_amount' => $order['grand_total'],
                );
            }
        }

        return $shippingGroups;
    }
}