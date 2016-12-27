<?php
/**
 * @category Qsolutions
 * @package Q_Admin
 * @author Michał Jóźwiak <michalj@qsolutionsstudio.com>
 */
class Q_Admin_Block_Adminhtml_Menu
    extends Mage_Adminhtml_Block_Page_Menu
{

    public function getMenuLevel($menu, $level = 0)
    {
        $html = '<ul ' . (!$level ? 'id="nav"' : '') . '>' . PHP_EOL;
        $z = 0;
        foreach ($menu as $item) {
            $html .= '<li class="item item_'
                . $z++.' item_'
                . strtolower($this->escapeHtml($item['label'])) .' '
                . (!$level && !empty($item['active']) ? ' active' : '') . ' '
                . (!empty($item['children']) ? ' parent' : '')
                . (!empty($level) && !empty($item['last']) ? ' last' : '')
                . ' level' . $level . '"> <a href="' . $item['url'] . '" '
                . (!empty($item['title']) ? 'title="' . $item['title'] . '"' : '') . ' '
                . (!empty($item['click']) ? 'onclick="' . $item['click'] . '"' : '') . ' class="'
                . ($level === 0 && !empty($item['active']) ? 'active' : '') . '"><span>'
                . $this->escapeHtml($item['label']) . '</span></a>' . PHP_EOL;

            if (!empty($item['children'])) {
                $html .= $this->getMenuLevel($item['children'], $level + 1);
            }
            $html .= '</li>' . PHP_EOL;
        }
        $html .= '</ul>' . PHP_EOL;

        return $html;
    }
}