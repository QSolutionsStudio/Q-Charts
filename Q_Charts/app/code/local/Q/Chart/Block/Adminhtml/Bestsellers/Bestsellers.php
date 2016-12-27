<?php
/**
 * @category Qsolutions
 * @package Q_Chart
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */

class Q_Chart_Block_Adminhtml_Bestsellers_Bestsellers
    extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * @var
     */
    protected $_from ;

    /**
     * @var
     */
    protected $_to;

    /**
     * @var
     */
    protected $_period;

    /**
     * @var
     */
    protected $_limit;

    /**
     * @param string $_from
     * @return $this
     */
    public function setFrom($_from)
    {
        $this->_from = $_from;

        return $this;
    }

    /**
     * @param string $_to
     * @return $this
     */
    public function setTo($_to)
    {
        $this->_to = $_to;

        return $this;
    }

    /**
     * @param string $_period
     * @return $this
     */
    public function setPeriod($_period)
    {
        $this->_period = $_period;

        return $this;
    }

    /**
     * @param string $_limit
     * @return $this
     */
    public function setLimit($_limit)
    {
        $this->_limit = $_limit;

        return $this;
    }

    /**
     * @return $this
     * @throws Exception
     */
    private function prepareData()
    {
        if (empty($this->_from) && empty($this->_to)) {
            $this->_from     = date('Y-m-01');
            $this->_to       = date('Y-m-d');
            $this->_period   = 'day';
            $this->_limit    = '5';
        }

        return $this;
    }

    /**
     * @return Mage_Sales_Model_Resource_Report_Bestsellers_Collection
     */
    protected function getBestsellerProduct()
    {
        $this->prepareData();
        $period = array(
            'from' => $this->_from,
            'to' => $this->_to,
        );

        $collection = Mage::getResourceModel('sales/report_bestsellers_collection')->setModel('catalog/product')
            ->setPeriod($this->_period)
            ->addFieldToFilter('period', $period);
        /* @var $collection Mage_Sales_Model_Resource_Report_Bestsellers_Collection */

        $collection->getSelect()
            ->order(array('period DESC'))
            ->limit($this->_limit)
        ;
        $collection->load();

        return $collection;
    }
}