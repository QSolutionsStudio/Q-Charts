<?php
/**
 * @category Qsolutions
 * @package Q_Admin
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Admin_Adminhtml_SalesbycountryController
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
        return Mage::getSingleton('admin/session')->isAllowed('advanced_reports/sales_by_country');
    }

    /**
     * @return $this
     */
    public function ajaxBlockAction()
    {
        $this->loadLayout();

        $countryFrom = $this->getRequest()->getParam('country_sales_from');
        $countryTo = $this->getRequest()->getParam('country_sales_to');

        $blockGraph = $this->getLayout()->getBlock('country_graph');
        $blockGraph->setFrom($countryFrom);
        $blockGraph->setTo($countryTo);

        $blockTable = $this->getLayout()->getBlock('country_table');
        $blockTable->setFrom($countryFrom);
        $blockTable->setTo($countryTo);

        $response = array(
            'graph' => $blockGraph->toHtml(),
            'table' => $blockTable->toHtml(),
        );

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode($response));

        return $this;
    }
}