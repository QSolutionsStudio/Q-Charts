<?php
/**
 * @category Qsolutions
 * @package Q_Admin
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Admin_Block_Adminhtml_Country_Tab_Graph
    extends Q_Admin_Block_Adminhtml_Country_Sales
{
    /**
     * @return Q_Admin_Helper_Adminhtml_Data
     */
    public function getDiagramHelper()
    {
        return Mage::helper('q_admin/adminhtml_data');
    }

    /**
     * @return Varien_Object
     */
    protected function getChartData()
    {
        $salesByCountryCollection = $this->getSalesByCountryCollection();

        $countryData = array();

        foreach ($salesByCountryCollection as $salesByCountry) {
            $countryData[] = array(
                'country_label' => $salesByCountry->getCountryName(),
                'country_items_qty' => $salesByCountry->getItemsQty(),
            );
        }

        return $countryData;
    }

}