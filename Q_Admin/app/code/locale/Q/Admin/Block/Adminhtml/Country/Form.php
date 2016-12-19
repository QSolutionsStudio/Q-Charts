<?php
/**
 * @category Qsolutions
 * @package Q_Admin
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Admin_Block_Adminhtml_Country_Form
    extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * @return string
     */
    protected function getTitle()
    {
        return $this->__('Sales by Country');
    }
}