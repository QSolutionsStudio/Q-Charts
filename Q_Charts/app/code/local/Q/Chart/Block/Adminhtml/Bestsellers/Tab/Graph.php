<?php
/**
 * @category Qsolutions
 * @package Q_Chart
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Chart_Block_Adminhtml_Bestsellers_Tab_Graph
    extends Q_Chart_Block_Adminhtml_Bestsellers_Bestsellers
{
    /**
     * @return Q_Chart_Helper_Adminhtml_Data
     */
    public function getDiagramHelper()
    {
        return Mage::helper('q_chart/adminhtml_data');
    }

    /**
     * @return array
     */
    private function getBestsellersCollection()
    {
        $bestsellersCollection = $this->getBestsellerProduct();
        $bestsellers = array();
        $dateArray = array();

        foreach ($bestsellersCollection as $bestseller) {
            $bestsellerPeriod = $bestseller->getPeriod();
            if(!in_array($bestsellerPeriod, $dateArray)) {
                $dateArray[] = $bestsellerPeriod;
            }
        }

        foreach ($dateArray as $period) {
            foreach ($bestsellersCollection as $bestseller ) {
                $productId = $bestseller->getProductId();

                if (!in_array($productId, $bestsellers) ||
                    $bestsellers[$period][$productId]['product_qty'] === 0) {
                    if ($bestseller->getPeriod() === $period) {
                        $qty = $bestseller->getQtyOrdered();
                    } else {
                        $qty = 0;
                    }

                    if (!isset($bestsellers[$period][$productId]) ||
                        $bestsellers[$period][$productId]['product_qty'] === 0) {
                        $bestsellers[$period][$productId] = array(
                            'product_name' => $bestseller->getProductName(),
                            'product_qty' => intval($qty),
                        );
                    }

                }
            }
        }

        return $bestsellers;
    }

    /**
     * @return array
     */
    protected function getChartColumns()
    {
        $bestsellersSortedData = $this->getBestsellersCollection();
        $chartColumns = array();

        foreach ( $bestsellersSortedData as  $key => $bestsellers) {
            foreach ($bestsellers as $key => $bestseller ) {
                $chartColumns[] = $bestseller['product_name'];
            }
            break;
        };

        return $chartColumns;
    }

    /**
     * @return array
     */
    protected function getChartRows()
    {
        $bestsellersSortedData = $this->getBestsellersCollection();
        $chartRows = array();

        foreach ($bestsellersSortedData as $key => $bestsellers) {
            $salesInPeriod = '';
            foreach ($bestsellers as $bestseller) {
                $salesInPeriod[] = $bestseller['product_qty'];
            }

            $chartRows[] = array(
                'period' => $key,
                'sales_in_period' => implode(', ', $salesInPeriod),
            );
        }

        return $chartRows;
    }
}