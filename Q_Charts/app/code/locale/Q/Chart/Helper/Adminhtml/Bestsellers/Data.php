<?php
/**
 * @category Qsolutions
 * @package Q_Chart
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */

class Q_Chart_Helper_Adminhtml_Bestsellers_Data
    extends Mage_Core_Helper_Abstract
{
    /**
     * @return array
     */
    public function getQty()
    {
        return array(
            '5',
            '10',
            '15',
            '20',
        );
    }

    /**
     * @return array
     */
    public function getPeriod()
    {
        return array(
            'day' => $this->__('Day'),
            'month' => $this->__('Month'),
            'year' => $this->__('Year'),
        );
    }
}