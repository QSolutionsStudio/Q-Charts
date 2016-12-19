<?php
/**
 * @category Qsolutions
 * @package Q_Admin
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Admin_Adminhtml_BestsellersController
    extends Mage_Adminhtml_Controller_Report_Abstract
{

    /**
     * @return $this
     */
    public function indexAction()
    {
        $this->_showLastExecutionTime(Mage_Reports_Model_Flag::REPORT_BESTSELLERS_FLAG_CODE, 'bestsellers');
        $this->loadLayout()->_setActiveMenu('advanced_reports/bestseller');
        $this->renderLayout();

        return $this;
    }

    /**
     * @return Mage_Admin_Model_Session
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('advanced_reports/bestseller');
    }

    /**
     * @return $this
     */
    public function ajaxBlockAction()
    {
        $this->loadLayout();

        $period = $this->getRequest()->getParam('period');
        $limit = $this->getRequest()->getParam('limit');
        $bestsellerFrom = $this->getRequest()->getParam('bestseller_from');
        $bestsellerTo = $this->getRequest()->getParam('bestseller_to');

        $blockGraph = $this->getLayout()->getBlock('bestseller_graph');
        $blockGraph->setFrom($bestsellerFrom);
        $blockGraph->setTo($bestsellerTo);
        $blockGraph->setPeriod($period);
        $blockGraph->setLimit($limit);

        $blockTable = $this->getLayout()->getBlock('bestseller_table');
        $blockTable->setFrom($bestsellerFrom);
        $blockTable->setTo($bestsellerTo);
        $blockTable->setPeriod($period);
        $blockTable->setLimit($limit);

        $response = array(
            'graph' => $blockGraph->toHtml(),
            'table' => $blockTable->toHtml(),
        );

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode($response));

        return $this;
    }
}