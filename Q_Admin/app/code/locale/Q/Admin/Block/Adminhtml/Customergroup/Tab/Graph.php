<?php
/**
 * @category Qsolutions
 * @package Q_Admin
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Admin_Block_Adminhtml_Customergroup_Tab_Graph
    extends Q_Admin_Block_Adminhtml_Customergroup_Customer
{
    /**
     * @return Q_Admin_Helper_Adminhtml_Data
     */
    public function getDiagramHelper()
    {
        return Mage::helper('q_admin/adminhtml_data');
    }

    /**
     * @return string
     */
    private function getAllSales()
    {
        $allSales = '';
        $salesByCustomerGroup = $this->getSalesQtyByCustomerGroup();

        foreach ($salesByCustomerGroup as $customerGroup) {
            $allSales += $customerGroup->getCustomerGroupSales();
        }
        
        return $allSales;
    }

    /**
     * @return Varien_Data_Collection
     * @throws Exception
     */
    protected function customerGroupSalesInPercent()
    {
        $allSales = $this->getAllSales();
        $salesByCustomerGroup = $this->getSalesQtyByCustomerGroup();

        $salesByCustomerGroupInPercent = new Varien_Data_Collection();

        foreach ($salesByCustomerGroup as $customerGroup) {
            $customerGroupSales = $customerGroup->getCustomerGroupSales();

            if ('0' !== $customerGroupSales) {
                $salesInPercent = round(($customerGroupSales/$allSales)*100, 2);
            } else {
                $salesInPercent = 0;
            }

            if (is_nan($salesInPercent) || $salesInPercent === 0) {
                continue;
            }

            $row = new Varien_Object();

            $row->setCustomerGroupId($customerGroup->getCustomerGroupId());
            $row->setCustomerGroupCode($customerGroup->getCustomerGroupCode());
            $row->setCustomerGroupSales($customerGroupSales);
            $row->setCustomerGroupSalesInPercent($salesInPercent);

            $salesByCustomerGroupInPercent->addItem($row);
        }

        return $salesByCustomerGroupInPercent;
    }
    
}