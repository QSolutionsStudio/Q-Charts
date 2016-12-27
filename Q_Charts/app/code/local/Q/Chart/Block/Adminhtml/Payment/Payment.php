<?php
/**
 * @category Qsolutions
 * @package Q_Chart
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Chart_Block_Adminhtml_Payment_Payment
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
    private $_storesIds;

    /**
     *
     */
    public function _construct()
    {
        parent::_construct();
        $this->setFrom(date('Y-m-01'));
        $this->setTo(date('Y-m-d'));
        $this->_storesIds = array_keys(Mage::app()->getStores());
    }

    /**
     * @param string $from
     * @return $this
     */
    public function setFrom($from)
    {
        $this->_from = $from;

        return $this;
    }

    /**
     * @param string $to
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
     * @return Q_Chart_Helper_Orders
     */
    private function getOrderCollection()
    {
        $this->adjustDate();
        $period = array(
            'from'  => $this->_from,
            'to'    => $this->_to,
        );

        $orders = Mage::helper('q_chart/orders')
            ->reset()
            ->setJoinedTable('sales_flat_order_payment')
            ->setPeriod($period)
            ->setColumns(array('sfop.method', 'sfop.amount_ordered'))
            ->getSalesFlatOrderWithJoins()
        ;
        /* @var $orders Q_Chart_Helper_Orders */

        return $orders;
    }

    /**
     * @return array
     */
    protected function getNecessaryData()
    {
        $orders = $this->getOrderCollection();
        $paymentGroups = array();
        foreach ($orders as $payment) {
            $paymentTitle = Mage::getStoreConfig(sprintf('payment/%s/title', $payment['method']));

            if (empty($paymentTitle)) {
                $paymentTitle = $payment['method'];
            }

            if(array_key_exists($payment['method'], $paymentGroups)){
                $paymentGroups[$payment['method']] = array(
                    'payment_title' => $paymentTitle,
                    'payment_qty'   => ++$paymentGroups[$payment['method']]['payment_qty'],
                    'orders_amount' => $paymentGroups[$payment['method']]['orders_amount'] + $payment['amount_ordered'],
                );
            } else {
                $paymentGroups[$payment['method']] = array(
                    'payment_title' => $paymentTitle,
                    'payment_qty'   => 1,
                    'orders_amount' => $payment['amount_ordered'],
                );
            }
        }

        return $paymentGroups;
    }
}