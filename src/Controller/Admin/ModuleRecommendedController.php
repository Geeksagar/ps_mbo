<?php
/**
 * 2007-2020 PrestaShop and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

namespace PrestaShop\Module\Mbo\Controller\Admin;

use PrestaShop\Module\Mbo\RecommendedModule\RecommendedModulePresenter;
use PrestaShop\Module\Mbo\Tab\TabCollectionProvider;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\Templating\EngineInterface;

class ModuleRecommendedController extends FrameworkBundleAdminController
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function indexAction(Request $request)
    {
        $response = new JsonResponse();
        /** @var EngineInterface $templateEngine */
        $templateEngine = $this->get('twig');

        try {
            /** @var TabCollectionProvider $tabCollectionProvider */
            $tabCollectionProvider = $this->get('mbo.tab.collection_provider');
            $tab = $tabCollectionProvider->getTab($request->get('tabClassName'));
            $recommendedModulePresenter = new RecommendedModulePresenter();
            $recommendedModulesInstalled = $tab->getRecommendedModulesInstalled();
            $recommendedModulesNotInstalled = $tab->getRecommendedModulesNotInstalled();
            $response->setData([
                'content' => $templateEngine->render(
                    '@Modules/ps_mbo/views/templates/admin/controllers/module_catalog/recommended-modules.html.twig',
                    [
                        'recommendedModulesInstalled' => $recommendedModulePresenter->presentCollection($recommendedModulesInstalled),
                        'recommendedModulesNotInstalled' => $recommendedModulePresenter->presentCollection($recommendedModulesNotInstalled),
                    ]
                ),
            ]);
        } catch (ServiceUnavailableHttpException $exception) {
            $response->setData([
                'content' => $templateEngine->render('@Modules/ps_mbo/views/templates/admin/error.html.twig'),
            ]);
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->add($exception->getHeaders());
        }

        return $response;
    }
}
