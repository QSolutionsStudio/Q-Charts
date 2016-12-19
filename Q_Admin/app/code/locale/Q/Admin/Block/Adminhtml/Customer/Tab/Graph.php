<?php
/**
 * @category Qsolutions
 * @package Q_Admin
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Admin_Block_Adminhtml_Customer_Tab_Graph
    extends Q_Admin_Block_Adminhtml_Customer_Customer
{
    /**
     * @var
     */
    protected $_registeredUsers;
    /**
     * @var
     */
    protected $_guestUsers;
    /**
     * @var
     */
    private $_allCustomers;

    /**
     * @return string
     */
    protected function _toHtml()
    {
        $this->separateDataToFitToChart();

        return parent::_toHtml();
    }

    /**
     * @return $this
     */
    private function setAllCustomersQty()
    {
        $this->_allCustomers = $this->_registeredUsers + $this->_guestUsers;

        return $this;
    }

    /**
     * @return Q_Admin_Helper_Adminhtml_Data
     */
    public function getDiagramHelper()
    {
        return Mage::helper('q_admin/adminhtml_data');
    }

    /**
     * @return array
     */
    private function customerCountBasedOnCollection()
    {
        $ordersCollection = $this->getOrderCollection();
        $customer = array(
            'guest' => 0,
            'registered' => 0,
        );

        foreach ($ordersCollection as $orders) {
            $customerId = $orders['customer_id'];
            if (is_null($customerId)) {
                $customer['guest'] += $orders['counted_customer'];
            } else {
                $customer['registered'] += $orders['counted_customer'];
            }
        }

        return $customer;
    }

    /**
     * @return $this
     */
    protected function separateDataToFitToChart()
    {
        $customerGroups = $this->customerCountBasedOnCollection();

        if (!empty($customerGroups)){
            $this->_registeredUsers  = $customerGroups['registered'];
            $this->_guestUsers       = $customerGroups['guest'];
            $this->setAllCustomersQty();
        }

        return $this;
    }
}