<?php

declare(strict_types=1);

namespace DrSoftFr\Module\CarrierProductBulk\UI\Admin\Controller;

use DrSoftFr\Module\CarrierProductBulk\Application\Command\AddCarriersToProductsCommand;
use DrSoftFr\Module\CarrierProductBulk\Application\Command\RemoveCarriersFromProductsCommand;
use DrSoftFr\Module\CarrierProductBulk\Application\CommandHandler\AddCarriersToProductsHandler;
use DrSoftFr\Module\CarrierProductBulk\Application\CommandHandler\RemoveCarriersFromProductsHandler;
use DrSoftFr\Module\CarrierProductBulk\Application\Dto\CarrierFilterDto;
use DrSoftFr\Module\CarrierProductBulk\Application\Dto\ProductFilterDto;
use DrSoftFr\Module\CarrierProductBulk\Application\Query\GetCarriersQuery;
use DrSoftFr\Module\CarrierProductBulk\Application\Query\GetCategoriesQuery;
use DrSoftFr\Module\CarrierProductBulk\Application\Query\GetManufacturersQuery;
use DrSoftFr\Module\CarrierProductBulk\Application\Query\GetProductsQuery;
use DrSoftFr\Module\CarrierProductBulk\Application\Query\GetSuppliersQuery;
use DrSoftFr\Module\CarrierProductBulk\Application\QueryHandler\GetCarriersHandler;
use DrSoftFr\Module\CarrierProductBulk\Application\QueryHandler\GetCategoriesHandler;
use DrSoftFr\Module\CarrierProductBulk\Application\QueryHandler\GetManufacturersHandler;
use DrSoftFr\Module\CarrierProductBulk\Application\QueryHandler\GetProductsHandler;
use DrSoftFr\Module\CarrierProductBulk\Application\QueryHandler\GetSuppliersHandler;
use drsoftfrcarrierproductbulk;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Security\Annotation\AdminSecurity;
use PrestaShopBundle\Security\Annotation\ModuleActivated;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class CarrierProductBulkController extends FrameworkBundleAdminController
{
    const TAB_CLASS_NAME = 'AdminDrSoftFrCarrierProductBulk';
    const PAGE_INDEX_ROUTE = 'admin_drsoft_fr_carrier_product_bulk_index';
    const TEMPLATE_FOLDER = '@Modules/drsoftfrcarrierproductbulk/views/templates/admin/';

    private AddCarriersToProductsHandler $addHandler;
    private RemoveCarriersFromProductsHandler $removeHandler;
    private GetCarriersHandler $getCarriersHandler;
    private GetCategoriesHandler $getCategoriesHandler;
    private GetManufacturersHandler $getManufacturersHandler;
    private GetProductsHandler $getProductsHandler;
    private GetSuppliersHandler $getSuppliersHandler;

    public function __construct(
        AddCarriersToProductsHandler      $addHandler,
        RemoveCarriersFromProductsHandler $removeHandler,
        GetCarriersHandler                $getCarriersHandler,
        GetCategoriesHandler              $getCategoriesHandler,
        GetManufacturersHandler           $getManufacturersHandler,
        GetProductsHandler                $getProductsHandler,
        GetSuppliersHandler               $getSuppliersHandler

    )
    {
        parent::__construct();

        $this->addHandler = $addHandler;
        $this->removeHandler = $removeHandler;
        $this->getCarriersHandler = $getCarriersHandler;
        $this->getCategoriesHandler = $getCategoriesHandler;
        $this->getManufacturersHandler = $getManufacturersHandler;
        $this->getProductsHandler = $getProductsHandler;
        $this->getSuppliersHandler = $getSuppliersHandler;
    }

    /**
     * @AdminSecurity(
     *     "is_granted(['read'], request.get('_legacy_controller'))",
     *     redirectRoute="admin_module_manage",
     *     message="Access denied."
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        $carrierFilterDto = new CarrierFilterDto([
            'limit' => 10
        ]);
        $productFilterDto = new ProductFilterDto([
            'limit' => 10
        ]);

        $carriers = $this->getCarriersHandler->handle(new GetCarriersQuery($carrierFilterDto));
        $products = $this->getProductsHandler->handle(new GetProductsQuery($productFilterDto));
        $categories = $this->getCategoriesHandler->handle(new GetCategoriesQuery(false));
        $manufacturers = $this->getManufacturersHandler->handle(new GetManufacturersQuery());
        $suppliers = $this->getSuppliersHandler->handle(new GetSuppliersQuery());

        return $this->render(self::TEMPLATE_FOLDER . 'index.html.twig', [
            'enableSidebar' => true,
            'help_link' => $this->generateSidebarLink($request->attributes->get('_legacy_controller')),
            'categories' => $categories,
            'carriers' => $carriers,
            'manufacturers' => $manufacturers,
            'products' => $products,
            'suppliers' => $suppliers,
            'module' => $this->getModule(),
        ]);
    }

    public function ajaxAction(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($data['action'] === 'add') {
            $this->addHandler->handle(new AddCarriersToProductsCommand($data['carriers'], $data['products']));
            return new JsonResponse(['message' => 'Transporteurs ajoutés avec succès !']);
        } elseif ($data['action'] === 'remove') {
            $this->removeHandler->handle(new RemoveCarriersFromProductsCommand($data['carriers'], $data['products']));
            return new JsonResponse(['message' => 'Transporteurs retirés avec succès !']);
        }

        return new JsonResponse(['message' => 'Action non reconnue.'], 400);
    }

    public function carrierListAction(Request $request): Response
    {
        $filter = new CarrierFilterDto($request->query->all());
        $objs = $this->getCarriersHandler->handle(new GetCarriersQuery($filter));

        return $this->render('@Modules/drsoftfrcarrierproductbulk/views/templates/admin/_carrier_list.html.twig', [
            'carriers' => $objs,
        ]);
    }

    public function productListAction(Request $request): Response
    {
        $filter = new ProductFilterDto($request->query->all());
        $objs = $this->getProductsHandler->handle(new GetProductsQuery($filter));

        return $this->render('@Modules/drsoftfrcarrierproductbulk/views/templates/admin/_product_list.html.twig', [
            'products' => $objs,
        ]);
    }

    /**
     * @return drsoftfrcarrierproductbulk
     */
    protected function getModule(): drsoftfrcarrierproductbulk
    {
        /** @type drsoftfrcarrierproductbulk */
        return $this->get('drsoft_fr.module.carrier_product_bulk.module');
    }
}
