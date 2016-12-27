<?php
/**
 * @category Qsolutions
 * @package Q_Chart
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Chart_Block_Adminhtml_Customergroup_Form
    extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * @return string
     */
    protected function getTitle()
    {
        return $this->__('Sales by Customer Group');
    }
}