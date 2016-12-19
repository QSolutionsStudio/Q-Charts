<?php
/**
 * @category Qsolutions
 * @package Q_Admin
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Admin_Adminhtml_ConversionController
    extends Mage_Adminhtml_Controller_Report_Abstract
{
    /**
     * @return $this
     */
    public function indexAction()
    {
        $this->_showLastExecutionTime(Mage_Reports_Model_Flag::REPORT_PRODUCT_VIEWED_FLAG_CODE, 'viewed');
        $this->loadLayout()->_setActiveMenu('advanced_reports/bestseller');
        $this->renderLayout();

        return $this;
    }

    /**
     * @return Mage_Admin_Model_Session
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('advanced_reports/conversion');
    }

    /**
     * @return $this
     */
    public function ajaxBlockAction()
    {
        $this->loadLayout();

        $conversionFrom = $this->getRequest()->getParam('conversion_from');
        $conversionTo = $this->getRequest()->getParam('conversion_to');

        $blockGraph = $this->getLayout()->getBlock('conversion_graph');
        $blockGraph->setFrom($conversionFrom);
        $blockGraph->setTo($conversionTo);
        $blockGraph->getConversion();

        $blockTable = $this->getLayout()->getBlock('conversion_table');
        $blockTable->setFrom($conversionFrom);
        $blockTable->setTo($conversionTo);
        $blockTable->getConversion();

        $response = array(
            'graph' => $blockGraph->toHtml(),
            'table' => $blockTable->toHtml(),
        );

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode($response));

        return $this;
    }
}