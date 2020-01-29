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

namespace PrestaShop\Module\Mbo\Bundle\Controller\Admin;

use PrestaShop\Module\Mbo\Adapter\ExternalContentProvider;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Security\Annotation\AdminSecurity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\Templating\EngineInterface;

/**
 * Responsible of "Improve > Modules > Modules Catalog" page display.
 */
class ModuleSelectionController extends FrameworkBundleAdminController
{
    /**
     * @AdminSecurity("is_granted('read', request.get('_legacy_controller'))")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $response = new Response();

        try {
            $externalContentProvider = new ExternalContentProvider();

            $content = $this->getTemplateEngine()->render(
                '@Modules/ps_mbo/views/templates/admin/controllers/module_catalog/addons_store.html.twig',
                [
                    'pageContent' => $externalContentProvider->getContent($this->getAddonsUrl($request)),
                    'layoutHeaderToolbarBtn' => [],
                    'layoutTitle' => $this->trans('Module selection', 'Admin.Navigation.Menu'),
                    'requireAddonsSearch' => true,
                    'requireBulkActions' => false,
                    'showContentHeader' => true,
                    'enableSidebar' => true,
                    'help_link' => $this->generateSidebarLink($request->attributes->get('_legacy_controller')),
                    'requireFilterStatus' => false,
                    'level' => $this->authorizationLevel($request->attributes->get('_legacy_controller')),
                ]
            );
            $response->setContent($content);
        } catch (ServiceUnavailableHttpException $exception) {
            $response->setContent($this->getTemplateEngine()->render('@Modules/ps_mbo/views/templates/admin/error.html.twig'));
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->add($exception->getHeaders());
        }

        return $response;
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    private function getAddonsUrl(Request $request)
    {
        $psVersion = $this->get('prestashop.core.foundation.version')->getVersion();
        $parent_domain = $request->getSchemeAndHttpHost();
        $context = $this->getContext();
        $currencyCode = $context->currency->iso_code;
        $languageCode = $context->language->iso_code;
        $countryCode = $context->country->iso_code;
        $activity = $this->get('prestashop.adapter.legacy.configuration')->getInt('PS_SHOP_ACTIVITY');

        return "https://addons.prestashop.com/iframe/search-1.7.php?psVersion=$psVersion"
            . "&isoLang=$languageCode"
            . "&isoCurrency=$currencyCode"
            . "&isoCountry=$countryCode"
            . "&activity=$activity"
            . "&parentUrl=$parent_domain"
        ;
    }

    /**
     * @return EngineInterface
     */
    private function getTemplateEngine()
    {
        return $this->get('templating');
    }
}
