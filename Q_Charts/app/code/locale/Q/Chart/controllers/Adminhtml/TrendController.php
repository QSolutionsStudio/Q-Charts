<?php
/**
 * @category Qsolutions
 * @package Q_Chart
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */

class Q_Chart_Adminhtml_TrendController
    extends Mage_Adminhtml_Controller_Action
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
        return Mage::getSingleton('admin/session')->isAllowed('advanced_reports/product_trend');
    }

    /**
     * @return $this
     */
    public function ajaxBlockAction()
    {
        $this->loadLayout();

        $trendFrom  = $this->getRequest()->getParam('trend_from');
        $trendTo    = $this->getRequest()->getParam('trend_to');
        $sku        = $this->getRequest()->getParam('sku');
        $period     = $this->getRequest()->getParam('period');

        $blockGraph = $this->getLayout()->getBlock('trend_graph');
        $blockGraph->setPeriod($period);
        $blockGraph->setFrom($trendFrom);
        $blockGraph->setTo($trendTo);
        $blockGraph->setSku($sku);
        $blockGraph->getOrdersCollection();

        $blockTable = $this->getLayout()->getBlock('trend_table');
        $blockTable->setPeriod($period);
        $blockTable->setFrom($trendFrom);
        $blockTable->setTo($trendTo);
        $blockTable->setSku($sku);
        $blockTable->getOrdersCollection();

        $response = array(
            'graph' => $blockGraph->toHtml(),
            'table' => $blockTable->toHtml(),
        );

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode($response));

        return $this;
    }
}