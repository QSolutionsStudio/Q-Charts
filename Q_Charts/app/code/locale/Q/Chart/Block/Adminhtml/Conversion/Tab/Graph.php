<?php
/**
 * @category Qsolutions
 * @package Q_Chart
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Chart_Block_Adminhtml_Conversion_Tab_Graph
    extends Q_Chart_Block_Adminhtml_Conversion_Conversion
{
    /**
     * @return Q_Chart_Helper_Adminhtml_Data
     */
    public function getDiagramHelper()
    {
        return Mage::helper('q_chart/adminhtml_data');
    }

    /**
     * @return Varien_Data_Collection
     * @throws Exception
     */
    public function getChartData()
    {
        $conversionArray = $this->getConversion();

        $conversionCollection = new Varien_Data_Collection();
        if (!is_null($conversionArray)) {
            foreach ($conversionArray as $key=>$conversion) {
                $row = new Varien_Object();
                $row->setMonth($key);
                $row->setConversion(round($conversion, 2));

                $conversionCollection->addItem($row);
            }
        }

        return $conversionCollection;
    }
}