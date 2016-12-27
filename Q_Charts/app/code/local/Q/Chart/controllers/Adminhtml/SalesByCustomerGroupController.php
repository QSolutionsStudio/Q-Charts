<?php
/**
 * @category Qsolutions
 * @package Q_Chart
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Chart_Adminhtml_SalesbycustomergroupController
    extends Mage_Adminhtml_Controller_Report_Abstract
{
    /**
     * @return $this
     */
    public function indexAction()
    {
        $this->loadLayout()->_setActiveMenu('advanced_reports/bestseller');
        $this->renderLayout();

        return $this;
    }

    /**
     * @return Mage_Admin_Model_Session
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('advanced_reports/sales_by_customer_group');
    }

    /**
     * @return $this
     */
    public function ajaxBlockAction()
    {
        $this->loadLayout();

        $customerFrom = $this->getRequest()->getParam('customer_group_from');
        $customerTo = $this->getRequest()->getParam('customer_group_to');

        $blockGraph = $this->getLayout()->getBlock('salescustomer_graph');
        $blockGraph->setFrom($customerFrom);
        $blockGraph->setTo($customerTo);

        $blockTable = $this->getLayout()->getBlock('salescustomer_table');
        $blockTable->setFrom($customerFrom);
        $blockTable->setTo($customerTo);

        $response = array(
            'graph' => $blockGraph->toHtml(),
            'table' => $blockTable->toHtml(),
        );

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode($response));

        return $this;
    }
}