<?php
/**
 * @category Data.php
 * @package Q_Chart
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Chart_Helper_Adminhtml_Dashboard_Data extends Mage_Adminhtml_Helper_Dashboard_Data
{
    /**
     * @return array
     */
    public function getDatePeriods()
    {
        return array(
            '24h'       => $this->__('Last 24 Hours'),
            '7d'        => $this->__('Last 7 Days'),
            '1m'        => $this->__('Current Month'),
            '1y'        => $this->__('YTD'),
            '2y'        => $this->__('2YTD'),
            'custom'    => $this->__('Custom Range')
        );
    }
}