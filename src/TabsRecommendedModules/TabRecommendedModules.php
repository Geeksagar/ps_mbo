<?php
/**
 * 2007-2019 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

namespace PrestaShop\Module\Mbo\TabsRecommendedModules;

use PrestaShop\Module\Mbo\RecommendedModules\RecommendedModulesInterface;

class TabRecommendedModules implements TabRecommendedModulesInterface
{
    /**
     * @var string class name of the tab
     */
    private $className;

    /**
     * @var string class name of the tab
     */
    private $displayMode;

    /**
     * @var RecommendedModulesInterface recommended modules of the tab
     */
    private $recommendedModules;

    /**
     * Tab constructor.
     *
     * @param string $className
     * @param string $displayMode
     * @param RecommendedModulesInterface $recommendedModules
     */
    public function __construct(
        $className,
        $displayMode,
        RecommendedModulesInterface $recommendedModules
    ) {
        $this->className = $className;
        $this->displayMode = $displayMode;
        $this->recommendedModules = $recommendedModules;
    }

    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayMode()
    {
        return $this->displayMode;
    }

    /**
     * {@inheritdoc}
     */
    public function getRecommendedModules()
    {
        return $this->recommendedModules;
    }

    /**
     * {@inheritdoc}
     */
    public function hasRecommendedModules()
    {
        return $this->recommendedModules
            && !$this->recommendedModules->isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function getRecommendedModulesInstalled()
    {
        if ($this->hasRecommendedModules()) {
            return $this->getRecommendedModules()->getInstalled();
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getRecommendedModulesNotInstalled()
    {
        if ($this->hasRecommendedModules()) {
            return $this->getRecommendedModules()->getNotInstalled();
        }

        return false;
    }
}
