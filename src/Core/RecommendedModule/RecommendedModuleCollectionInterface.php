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

namespace PrestaShop\Module\Mbo\Core\RecommendedModule;

use ArrayAccess;
use Countable;
use IteratorAggregate;

interface RecommendedModuleCollectionInterface extends ArrayAccess, IteratorAggregate, Countable
{
    /**
     * Add a recommended module to this collection.
     *
     * @param RecommendedModuleInterface $recommendedModule
     *
     * @return self
     */
    public function addRecommendedModule(RecommendedModuleInterface $recommendedModule);

    /**
     * Get a recommended module by name
     *
     * @param string $moduleName
     *
     * @return RecommendedModuleInterface|false
     */
    public function getRecommendedModule($moduleName);

    /**
     * Get names of recommended modules
     *
     * @return string[]
     */
    public function getRecommendedModuleNames();

    /**
     * @param mixed $offset
     *
     * @return RecommendedModuleInterface
     */
    public function offsetGet($offset);

    /**
     * @return bool
     */
    public function isEmpty();

    /**
     * Sort recommended modules by position
     */
    public function sortByPosition();

    /**
     * Get recommended modules installed.
     *
     * @return RecommendedModuleInterface[]
     */
    public function getInstalled();

    /**
     * Get recommended modules not installed.
     *
     * @return RecommendedModuleInterface[]
     */
    public function getNotInstalled();
}
