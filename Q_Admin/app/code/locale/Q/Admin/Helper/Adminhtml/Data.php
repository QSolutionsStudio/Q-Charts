<?php
/**
 * @category Qsolutions
 * @package Q_Admin
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */

class Q_Admin_Helper_Adminhtml_Data
    extends Mage_Core_Helper_Abstract
{

    /**
     * @var array
     */
    private  $R = array(
        'max' => 232,
        'min' =>  255,
    );

    /**
     * @var array
     */
    private $G = array(
        'max' => 207,
        'min' =>  73,
    );

    /**
     * @var array
     */
    private $B = array(
        'max' => 186,
        'min' =>  0,
    );

    /**
     * @return array
     */
    public function getColor()
    {
        return array(
            '255, 107, 35',
            '35, 255, 236',
            '178, 58, 0',
            '7, 178, 164',
            '255, 152, 103',
            '48, 183, 255',
            '255, 194, 116',
            '9, 119, 178',
            '178, 120, 0',
            '42, 123, 255',
            '255, 186, 42',
            '8, 73, 178',
            '178, 133, 1',
            '42, 85, 255',
            '255, 200, 42',
            '8, 178, 104',
            '255, 149, 139',
            '178, 39, 15',
            '63, 255, 171',
            '255, 90, 63',
        );
    }

    /**
     * @param  string $counter
     * @return string
     */
    public function colorManipulator($counter)
    {
        $qty = $counter;

        if ($qty !== 0) {
            $rJump = ceil(($this->R['max']-$this->R['min'])/$qty);
            $rRange = range($this->R['min'], $this->R['max'], $rJump);

            $gJump = ceil(($this->G['max']-$this->G['min'])/$qty);
            $gRange = range($this->G['min'], $this->G['max'], $gJump);

            $bJump = ceil(abs(($this->B['max']-$this->B['min']))/$qty);
            $bRange = range($this->B['min'], $this->B['max'], $bJump);

            $rgb = array();

            for ($i = 0; $i <= $qty; $i++) {
                $r = $rRange[$i];
                if (empty($rRange[$i])) {
                    $r = reset($rRange);
                }

                if (!empty($gRange[$i])) {
                    $g = $gRange[$i];
                } else {
                    $g = reset($gRange);
                }

                if (!empty($bRange[$i])) {
                    $b = $bRange[$i];
                } else {
                    $b = reset($bRange);
                }

                $rgb[] = sprintf('rgb(%s, %s, %s)', $r, $g, $b);
            }

            return json_encode($rgb);
        }
    }
}