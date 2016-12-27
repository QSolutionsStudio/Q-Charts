<?php
/**
 * @category Qsolutions
 * @package Q_Chart
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Chart_Block_Adminhtml_Country_Tab_Graph
    extends Q_Chart_Block_Adminhtml_Country_Sales
{
    /**
     * @return Q_Chart_Helper_Adminhtml_Data
     */
    public function getDiagramHelper()
    {
        return Mage::helper('q_chart/adminhtml_data');
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