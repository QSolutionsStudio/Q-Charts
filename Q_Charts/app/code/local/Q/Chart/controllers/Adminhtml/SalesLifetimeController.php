<?php
/**
 * @category Qsolutions
 * @package Q_Chart
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Chart_Adminhtml_SaleslifetimeController
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
        return Mage::getSingleton('admin/session')->isAllowed('advanced_reports/sales_lifetime_state');
    }
}