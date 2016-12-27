<?php
/**
 * @category Qsolutions
 * @package Q_Chart
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Chart_Block_Adminhtml_Payment_Tab_Graph
    extends Q_Chart_Block_Adminhtml_Payment_Payment
{
    /**
     * @return Q_Chart_Helper_Adminhtml_Data
     */
    public function getDiagramHelper()
    {
        return Mage::helper('q_chart/adminhtml_data');
    }

    /**
     * @return int
     */
    private function getAddedPaymentsQty()
    {
        $paymentNecessaryData = $this->getNecessaryData();
        $allPayments = 0;

        foreach ($paymentNecessaryData as $paymentData) {
            $allPayments += $paymentData['payment_qty'];
        }

        return $allPayments;
    }

    /**
     * @return Varien_Data_Collection
     * @throws Exception
     */
    protected function getDataAdjustedToShow()
    {
        $paymentNecessaryData = $this->getNecessaryData();
        $allPayments = $this->getAddedPaymentsQty();

        $chartData = new Varien_Data_Collection();

        foreach ($paymentNecessaryData as $key => $paymentData) {
            $paymentInPercent = round(($paymentData['payment_qty']/$allPayments)*100, 2);

            $row = new Varien_Object();

            $row->setPaymentMethodCode($key);
            $row->setPaymentMethodTitle($paymentData['payment_title']);
            $row->setPaymentQtyInPeriod($paymentData['payment_qty']);
            $row->setOrdersAmountInPeriod($paymentData['orders_amount']);
            $row->setPaymentMethodUsesInPercent($paymentInPercent);

            $chartData->addItem($row);
        }

        return $chartData;
    }
}