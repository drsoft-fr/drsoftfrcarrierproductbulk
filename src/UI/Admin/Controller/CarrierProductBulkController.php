<?php

namespace DrSoftFr\Module\CarrierProductBulk\UI\Admin\Controller;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use PrestaShopBundle\Security\Annotation\AdminSecurity;

class CarrierProductBulkController extends FrameworkBundleAdminController
{
    const TAB_CLASS_NAME = 'AdminDrSoftFrCarrierProductBulk';

    /**
     * @Route(
     *     "/admin/drsoft-fr/carrier-product-bulk",
     *     name="drsoft_fr_carrier_product_bulk_admin"
     * )
     * @AdminSecurity("is_granted('read', request.get('_legacy_controller'))")
     */
    public function index(): Response
    {
        return $this->render('@Modules/drsoftfrcarrierproductbulk/views/templates/admin/index.html.twig');
    }
}
