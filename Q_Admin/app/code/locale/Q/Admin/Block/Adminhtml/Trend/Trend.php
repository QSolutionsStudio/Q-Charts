<?php
/**
 * @category Qsolutions
 * @package Q_Admin
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Admin_Block_Adminhtml_Trend_Trend
    extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * @var
     */
    protected $_from;
    /**
     * @var
     */
    protected $_to;
    /**
     * @var
     */
    protected $_sku;
    /**
     * @var
     */
    protected $_period;
    /**
     * @var
     */
    protected $_storesIds;

    /**
     *
     */
    public function _construct()
    {
        parent::_construct();
        $this->setPeriod('day');
        $this->setFrom(date('Y-m-d', strtotime("-1 month")));
        $this->setTo(date('Y-m-d'));
        $this->_storesIds = array_keys(Mage::app()->getStores());
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
     * @param string $sku
     * @return $this
     */
    public function setSku($sku)
    {
        $this->_sku = $sku;

        return $this;
    }

    /**
     * @param string $period
     * @return $this
     */
    public function setPeriod($period)
    {
        $this->_period = $period;

        return $this;
    }

    /**
     * @return $this
     */
    private function adjustDate()
    {
        if ($this->_period === 'day') {
            $this->setFrom(date('Y-m-d 00:00:00', strtotime($this->_from)));
            $this->setTo(date('Y-m-d 23:59:59', strtotime($this->_to)));
        } elseif ($this->_period === 'month') {
            $this->setFrom(date('Y-m-01 00:00:00', strtotime($this->_from)));
            $this->setTo(date('Y-m-t 23:59:59', strtotime($this->_to)));
        } elseif ($this->_period === 'year') {
            $this->setFrom(date('Y-01-01 00:00:00', strtotime($this->_from)));
            $this->setTo(date('Y-12-31 23:59:59', strtotime($this->_to)));
        }

        return $this;
    }

    /**
     * @return false|Mage_Catalog_Model_Product
     */
    protected function getProductId()
    {
        return Mage::getModel('catalog/product')->getIdBySku($this->_sku);
    }

    /**
     * @param $productId
     * @return array
     */
    protected function getProductSku($productId)
    {
        $sku = [];
        $productCollection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToFilter('entity_id', array('in' => $productId))
        ;
        /* @var $productCollection Mage_Catalog_Model_Resource_Product */

        foreach ($productCollection as $product) {
            $sku = $product->getSku();
        }

        return $sku;
    }

    /**
     * @param $productId
     * @return Q_Admin_Model_Relatedproduct
     */
    protected function getConfigurableProduct($productId)
    {
        return Mage::getModel('q_admin/relatedproduct')->getCollection()
            ->addFieldToFilter('parent_id', $productId)
            ;
    }

    /**
     * @param $productId
     * @return Mage_Catalog_Model_Product
     */
    protected function getBundleProducts($productId)
    {
        return Mage::getModel('catalog/product')
            ->getCollection()
            ->addFieldToFilter('type_id','bundle')
            ->addFieldToFilter('entity_id', $productId)
        ;
    }

    /**
     * @param $productId
     * @return array
     */
    protected function getChildrenBundleProduct($productId)
    {
        $products = $this->getBundleProducts($productId);
        $ids = array();

        foreach($products as $product)
        {
            $childrenIdsByOption = $product
                ->getTypeInstance($product)
                ->getChildrenIds($product->getId(),false);

            foreach($childrenIdsByOption as $array)
            {
                $ids = array_merge($ids, $array);
            }
        }

        return $ids;
    }

    /**
     * @return Q_Admin_Model_Relatedproduct
     */
    protected function checkRelatedProduct()
    {
        $productId = $this->getProductId();
        $relatedProducts = array();

        if (count($this->getConfigurableProduct($productId)) > 0) {
            $relatedProducts = $this->getConfigurableProduct($productId);
        } elseif (count($this->getBundleProducts($productId)) > 0) {
            $relatedProducts = $this->getChildrenBundleProduct($productId);
        }

        return $relatedProducts;
    }

    /**
     * @return Varien_Data_Collection
     * @throws Exception
     */
    protected function getOrderItemCollection()
    {
        $this->adjustDate();
        $period = array(
            'from'  => $this->_from,
            'to'    => $this->_to,
        );
        $relatedProducts = $this->checkRelatedProduct();

        $productTrendCollection = new Varien_Data_Collection();

        if (!is_null($this->_sku)) {
            if (!count($relatedProducts) > 0){
                $relatedProducts = array($this->getProductId());
            }
            foreach ($relatedProducts as $product) {
                if ($relatedProducts instanceof Q_Admin_Model_Resource_Relatedproduct_Collection) {
                    $productSku = $this->getProductSku($product->getProductId());
                } else {
                    $productSku = $this->getProductSku($product);
                }

                $orders =  Mage::getResourceModel('sales/order_item_collection')
                    ->addFilterToMap('created_at', 'main_table.created_at')
                    ->addFilterToMap('store_id', 'main_table.store_id')
                    ->addFilterToMap('sku', 'main_table.sku')
                    ->addFieldToFilter('created_at', $period)
                    ->addFieldToFilter('parent_item_id', array('null' => true))
                    ->addFieldToFilter('store_id', $this->_storesIds)
                    ->addFieldToFilter('sku', $productSku)
                ;
                /* @var $orders Mage_Sales_Model_Resource_Order_Item_Collection */

                $orders->getSelect()
                    ->columns('SUM(main_table.qty_ordered) as counted_items')
                    ->columns('SUM(main_table.row_total) as total_payments')
                    ->join(array('orders'=> 'sales_flat_order'), 'orders.entity_id = main_table.order_id', array('orders.status'))
                    ->where('orders.status = ?', Mage_Sales_Model_Order::STATE_COMPLETE)
                    ->group(sprintf('SUBSTR(main_table.created_at, 1, %d)', $this->getLengthBasedOnPeriod()))
                ;

                $row = new Varien_Object();
                $row->setTrendCollection($orders);

                $productTrendCollection->addItem($row);
            }
        }

        return $productTrendCollection;
    }

    /**
     * @return string
     */
    private function getLengthBasedOnPeriod()
    {
        $period = $this->_period;

        if ($period === 'month') {
            return '7';
        }

        if ($period === 'year') {
            return '4';
        }

        return '10';
    }
}