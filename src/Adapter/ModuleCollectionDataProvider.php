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

namespace PrestaShop\Module\Mbo\Adapter;

use Module;
use PrestaShop\PrestaShop\Adapter\LegacyContext;
use PrestaShop\PrestaShop\Adapter\Module\AdminModuleDataProvider;
use PrestaShop\PrestaShop\Adapter\Presenter\PresenterInterface;
use PrestaShop\PrestaShop\Core\Addon\AddonsCollection;
use PrestaShop\PrestaShop\Core\Addon\Module\ModuleRepository;
use PrestaShop\PrestaShop\Core\Addon\Module\ModuleRepositoryInterface;
use PrestaShopBundle\Entity\Repository\TabRepository;
use Profile;

class ModuleCollectionDataProvider
{
    /**
     * @var AdminModuleDataProvider
     */
    private $addonsProvider;

    /**
     * @var ModuleRepositoryInterface
     */
    private $moduleRepository;

    /**
     * @var PresenterInterface
     */
    private $modulePresenter;

    /**
     * @var TabRepository
     */
    private $tabRepository;

    /**
     * @var LegacyContext
     */
    private $context;

    /**
     * Constructor.
     *
     * @param AdminModuleDataProvider $addonsProvider
     * @param ModuleRepositoryInterface $moduleRepository
     * @param PresenterInterface $modulePresenter
     * @param TabRepository $tabRepository
     * @param LegacyContext $context
     */
    public function __construct(
        AdminModuleDataProvider $addonsProvider,
        ModuleRepositoryInterface $moduleRepository,
        PresenterInterface $modulePresenter,
        TabRepository $tabRepository,
        LegacyContext $context
    ) {
        $this->addonsProvider = $addonsProvider;
        $this->moduleRepository = $moduleRepository;
        $this->modulePresenter = $modulePresenter;
        $this->tabRepository = $tabRepository;
        $this->context = $context;
    }

    /**
     * @param array $moduleNames
     *
     * @return array
     */
    public function getData(array $moduleNames)
    {
        $data = [];

        $modulesOnDisk = AddonsCollection::createFrom($this->moduleRepository->getList());
        $modulesOnDisk = $this->addonsProvider->generateAddonsUrls($modulesOnDisk);

        foreach ($modulesOnDisk as $module) {
            if (in_array($module->get('name'), $moduleNames)) {
                $perm = true;
                if ($module->get('id')) {
                    $perm &= Module::getPermissionStatic(
                        $module->get('id'),
                        'configure',
                        $this->context->getContext()->employee
                    );
                } else {
                    $id_admin_module = $this->tabRepository->findOneIdByClassName('AdminModules');
                    $access = Profile::getProfileAccess(
                        $this->context->getContext()->employee->id_profile,
                        $id_admin_module
                    );

                    $perm &= !$access['edit'];
                }

                if ($module->get('author') === ModuleRepository::PARTNER_AUTHOR) {
                    $module->set('type', 'addonsPartner');
                }

                if ($perm) {
                    $module->fillLogo();
                    $data[$module->get('name')] = $this->modulePresenter->present($module);
                }
            }
        }

        return $data;
    }
}
