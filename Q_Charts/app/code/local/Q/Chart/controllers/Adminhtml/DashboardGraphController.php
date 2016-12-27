<?php
/**
 * @category Qsolutions
 * @package Q_Chart
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */

class Q_Chart_Adminhtml_DashboardgraphController
    extends Mage_Adminhtml_Controller_Action
{
    /**
     * @return Mage_Admin_Model_Session
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('dashboard');
    }
    /**
     * @return $this
     */
    public function ajaxBlockAction()
    {
        $this->loadLayout();

        $dashboardFrom  = $this->getRequest()->getParam('dashboard_from');
        $dashboardTo    = $this->getRequest()->getParam('dashboard_to');
        $range          = $this->getRequest()->getParam('range');

        $blockTable = $this->getLayout()->createBlock('adminhtml/dashboard_totals');
        $blockTable->setRange($range);
        if ($range === 'custom') {
            $blockTable->setFrom($dashboardFrom);
            $blockTable->setTo($dashboardTo);
        }

        $blockOrders = $this->getLayout()->createBlock('adminhtml/dashboard_tab_orders');
        $blockOrders->setRange($range);
        if ($range === 'custom') {
            $blockOrders->setFrom($dashboardFrom);
            $blockOrders->setTo($dashboardTo);
        }

        $blockAmounts = $this->getLayout()->createBlock('adminhtml/dashboard_tab_amounts');
        $blockAmounts->setRange($range);
        if ($range === 'custom') {
            $blockAmounts->setFrom($dashboardFrom);
            $blockAmounts->setTo($dashboardTo);
        }

        $response = array(
            'orders' => $blockOrders->toHtml(),
            'amounts' => $blockAmounts->toHtml(),
            'table' => $blockTable->toHtml(),
        );


        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode($response));

        return $this;
    }
}