<?php
/**
 * @category Qsolutions
 * @package Q_Chart
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Chart_Block_Adminhtml_Bestsellers_Tab_Table
    extends Q_Chart_Block_Adminhtml_Bestsellers_Bestsellers
{
    /**
     *
     */
    public function _construct()
    {
        $this->setTemplate('qsolutions/bestsellers/table.phtml');
    }
}