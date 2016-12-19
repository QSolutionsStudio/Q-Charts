<?php
/**
 * @category Qsolutions
 * @package Q_Admin
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Admin_Block_Adminhtml_Conversion_Conversion
    extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * @var
     */
    protected $_mostViewedGroupByDate;

    /**
     * @var
     */
    protected $_totalOrderedCountByDate;

    /**
     * @var
     */
    protected $_conversion;

    /**
     * @var
     */
    protected $_storesIds;

    /**
     * @var
     */
    protected $_from;

    /**
     * @var
     */
    protected $_to;

    /**
     *
     */
    public function _construct()
    {
        parent::_construct();
        $this->setFrom(date('Y-m-01'));
        $this->setTo(date('Y-m-d'));
    }

    /**
     * @return $this
     */
    protected function getConversion()
    {
        $this->setStoresIds();
        $this->getMostViewedCollection();
        $this->setDateRangeToOrderMonthly();

        foreach ($this->_mostViewedGroupByDate as $viewKey => $viewsQty) {
            foreach ($this->_totalOrderedCountByDate as $orderKey => $orderQty) {
                if ($viewKey === $orderKey) {
                    $conversionVal = $orderQty/$viewsQty;
                    $this->_conversion[$viewKey] = round($conversionVal, 5);
                }
            }
        }

        return $this->_conversion;
    }

    /**
     * @param string $from
     * @return $this
     */
    public function setFrom($from)
    {
        $this->_from = $from;

        return $this;
    }

    /**
     * @param string $to
     * @return $this
     */
    public function setTo($to)
    {
        $this->_to = $to;

        return $this;
    }

    /**
     * @return $this
     */
    protected function setStoresIds()
    {
        $this->_storesIds = array_keys(Mage::app()->getStores());

        return $this;
    }

    /**
     * @return Mage_Reports_Model_Resource_Report_Product_Viewed_Collection
     */
    protected function getMostViewedCollection()
    {
        $period = array(
            'from' => $this->_from,
            'to' => $this->_to,
        );

        $resource = Mage::getSingleton('core/resource');
        /* @var $resource Mage_Core_Model_Resource */

        $readConnection = $resource->getConnection('core_read');

        $views = $readConnection->select()
            ->from(array('rvpam' => $resource->getTableName('report_viewed_product_aggregated_monthly')), array('period', "SUM(`views_num`)"))
            ->where("rvpam.period >= ?", date('Y-m-d', strtotime($period['from'])))
            ->where("rvpam.period <= ?", date('Y-m-d', strtotime($period['to'])))
            ->group('SUBSTR(period, 1, 7)')
        ;

        $viewsCollection = $readConnection->fetchAll($views);

        $this->setMostViewedGroupByDate($viewsCollection);
        return $viewsCollection;
    }

    /**
     * @param $collection
     * @return $this
     */
    protected function setMostViewedGroupByDate($collection)
    {
        $final = array();

        foreach ($collection as $views) {
            $final[$views['period']] = $views['SUM(`views_num`)'];
        }

        $this->_mostViewedGroupByDate = $final;

        return $this;
    }

    /**
     * @return $this
     */
    protected function setDateRangeToOrderMonthly()
    {
        $dates          = array_keys($this->_mostViewedGroupByDate);
        $orderPeriod    = array();

        foreach ($dates as $date) {
            $orderPeriod[$date]['start_date']  = date('Y-m-01', strtotime($date));
            $orderPeriod[$date]['end_date']    = date('Y-m-t', strtotime($date));
        }

        $this->setOrdersCompletedAggregatedMonthly($orderPeriod);

        return $this;
    }

    /**
     * @param $orderPeriod
     * @return $this
     */
    protected function setOrdersCompletedAggregatedMonthly($orderPeriod)
    {
        $totalSum = array();

        foreach ($orderPeriod as $key => $dateRange) {
            $period = array(
                'from'  => $dateRange['start_date'],
                'to'    => $dateRange['end_date'],
            );

            $orders = Mage::helper('q_admin/orders')
                ->reset()
                ->setPeriod($period)
                ->getSalesFlatOrderWithJoins()
            ;
            /* @var $orders Q_Admin_Helper_Orders */

            $totalSum[$key] = count($orders);
        }

        $this->_totalOrderedCountByDate = $totalSum;

        return $this;
    }
}