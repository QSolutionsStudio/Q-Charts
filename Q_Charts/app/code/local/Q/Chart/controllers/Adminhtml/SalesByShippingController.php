<?php
/**
 * @category Qsolutions
 * @package Q_Chart
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Chart_Adminhtml_SalesbyshippingController
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
        return Mage::getSingleton('admin/session')->isAllowed('advanced_reports/sales_by_shipping_method');
    }

    /**
     * @return $this
     */
    public function ajaxBlockAction()
    {
        $this->loadLayout();

        $shippingFrom = $this->getRequest()->getParam('shipping_from');
        $shippingTo = $this->getRequest()->getParam('shipping_to');

        $blockGraph = $this->getLayout()->getBlock('shipping_graph');
        $blockGraph->setFrom($shippingFrom);
        $blockGraph->setTo($shippingTo);

        $blockTable = $this->getLayout()->getBlock('shipping_table');
        $blockTable->setFrom($shippingFrom);
        $blockTable->setTo($shippingTo);

        $response = array(
            'graph' => $blockGraph->toHtml(),
            'table' => $blockTable->toHtml(),
        );

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode($response));

        return $this;
    }
}