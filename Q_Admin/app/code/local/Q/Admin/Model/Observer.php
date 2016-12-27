<?php
/**
 * @category Qsolutions
 * @package Q_Admin
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */

class Q_Admin_Model_Observer
{
    /**
     * @throws Mage_Core_Exception
     */
    public function setTheme()
    {
        $theme = Mage::getStoreConfig('q_admin/config/theme');
        Mage::getDesign()->setTheme($theme);
    }
}