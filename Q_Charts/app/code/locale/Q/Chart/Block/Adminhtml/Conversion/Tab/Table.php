<?php
/**
 * @category Qsolutions
 * @package Q_Chart
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Chart_Block_Adminhtml_Conversion_Tab_Table
    extends Q_Chart_Block_Adminhtml_Conversion_Conversion
{
    /**
     * @return Varien_Data_Collection
     * @throws Exception
     */
    public function getTableData()
    {
        $this->getConversion();
        $viewsQty   = $this->_mostViewedGroupByDate;
        $ordersQty  = $this->_totalOrderedCountByDate;
        $conversion = $this->_conversion;

        $tableData = new Varien_Data_Collection();
        if (!empty($viewsQty)) {
            foreach ($viewsQty as $date => $qty) {
                $row = new Varien_Object();
                $row->setPeriod($date);
                $row->setOrdersQty($ordersQty[$date]);
                $row->setViewsQty($qty);
                $row->setConversion(round((float)$conversion[$date] * 100 ));

                $tableData->addItem($row);
            }
        }

        return $tableData;
    }
}