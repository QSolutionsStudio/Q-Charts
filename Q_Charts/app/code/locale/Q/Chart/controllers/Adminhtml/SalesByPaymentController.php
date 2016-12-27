<?php
/**
 * @category Qsolutions
 * @package Q_Chart
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Chart_Adminhtml_SalesbypaymentController
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
        return Mage::getSingleton('admin/session')->isAllowed('advanced_reports/sales_by_pamymet_method');
    }

    /**
     * @return $this
     */
    public function ajaxBlockAction()
    {
        $this->loadLayout();

        $paymentFrom = $this->getRequest()->getParam('payment_from');
        $paymentTo = $this->getRequest()->getParam('payment_to');

        $blockGraph = $this->getLayout()->getBlock('payment_graph');
        $blockGraph->setFrom($paymentFrom);
        $blockGraph->setTo($paymentTo);

        $blockTable = $this->getLayout()->getBlock('payment_table');
        $blockTable->setFrom($paymentFrom);
        $blockTable->setTo($paymentTo);

        $response = array(
            'graph' => $blockGraph->toHtml(),
            'table' => $blockTable->toHtml(),
        );

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode($response));

        return $this;
    }
}