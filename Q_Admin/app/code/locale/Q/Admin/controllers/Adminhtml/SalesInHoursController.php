<?php
/**
 * @category Qsolutions
 * @package Q_Admin
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Admin_Adminhtml_SalesinhoursController
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
        return Mage::getSingleton('admin/session')->isAllowed('advanced_reports/sales_in_hours');
    }

    /**
     * @return $this
     */
    public function ajaxBlockAction()
    {
        $this->loadLayout();

        $hoursFrom = $this->getRequest()->getParam('hours_from');
        $hoursTo = $this->getRequest()->getParam('hours_to');

        $blockGraph = $this->getLayout()->getBlock('hours_graph');
        $blockGraph->setFrom($hoursFrom);
        $blockGraph->setTo($hoursTo);

        $blockTable = $this->getLayout()->getBlock('hours_table');
        $blockTable->setFrom($hoursFrom);
        $blockTable->setTo($hoursTo);

        $response = array(
            'graph' => $blockGraph->toHtml(),
            'table' => $blockTable->toHtml(),
        );

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode($response));

        return $this;
    }
}