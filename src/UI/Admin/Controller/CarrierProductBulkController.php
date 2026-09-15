<?php

declare(strict_types=1);

namespace DrSoftFr\Module\CarrierProductBulk\UI\Admin\Controller;

use DrSoftFr\Module\CarrierProductBulk\Application\Command\AddCarriersToProductsCommand;
use DrSoftFr\Module\CarrierProductBulk\Application\Command\RemoveCarriersFromProductsCommand;
use DrSoftFr\Module\CarrierProductBulk\Application\CommandHandler\AddCarriersToProductsHandler;
use DrSoftFr\Module\CarrierProductBulk\Application\CommandHandler\RemoveCarriersFromProductsHandler;
use DrSoftFr\Module\CarrierProductBulk\Application\Dto\CarrierFilterDto;
use DrSoftFr\Module\CarrierProductBulk\Application\Dto\ProductFilterDto;
use DrSoftFr\Module\CarrierProductBulk\Application\Query\CountCarriersQuery;
use DrSoftFr\Module\CarrierProductBulk\Application\Query\CountProductsQuery;
use DrSoftFr\Module\CarrierProductBulk\Application\Query\GetCarriersQuery;
use DrSoftFr\Module\CarrierProductBulk\Application\Query\GetCategoriesQuery;
use DrSoftFr\Module\CarrierProductBulk\Application\Query\GetManufacturersQuery;
use DrSoftFr\Module\CarrierProductBulk\Application\Query\GetProductsQuery;
use DrSoftFr\Module\CarrierProductBulk\Application\Query\GetSuppliersQuery;
use DrSoftFr\Module\CarrierProductBulk\Application\QueryHandler\CountCarriersHandler;
use DrSoftFr\Module\CarrierProductBulk\Application\QueryHandler\CountProductsHandler;
use DrSoftFr\Module\CarrierProductBulk\Application\QueryHandler\GetCarriersHandler;
use DrSoftFr\Module\CarrierProductBulk\Application\QueryHandler\GetCategoriesHandler;
use DrSoftFr\Module\CarrierProductBulk\Application\QueryHandler\GetManufacturersHandler;
use DrSoftFr\Module\CarrierProductBulk\Application\QueryHandler\GetProductsHandler;
use DrSoftFr\Module\CarrierProductBulk\Application\QueryHandler\GetSuppliersHandler;
use drsoftfrcarrierproductbulk;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Security\Annotation\AdminSecurity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class CarrierProductBulkController extends FrameworkBundleAdminController
{
    const TAB_CLASS_NAME = 'AdminDrSoftFrCarrierProductBulk';
    const TEMPLATE_FOLDER = '@Modules/drsoftfrcarrierproductbulk/src/UI/Admin/View/';

    private drsoftfrcarrierproductbulk $module;
    private AddCarriersToProductsHandler $addHandler;
    private RemoveCarriersFromProductsHandler $removeHandler;
    private GetCarriersHandler $getCarriersHandler;
    private GetCategoriesHandler $getCategoriesHandler;
    private GetManufacturersHandler $getManufacturersHandler;
    private GetProductsHandler $getProductsHandler;
    private GetSuppliersHandler $getSuppliersHandler;
    private CountCarriersHandler $countCarriersHandler;
    private CountProductsHandler $countProductsHandler;

    public function __construct(
        drsoftfrcarrierproductbulk        $module,
        AddCarriersToProductsHandler      $addHandler,
        RemoveCarriersFromProductsHandler $removeHandler,
        GetCarriersHandler                $getCarriersHandler,
        GetCategoriesHandler              $getCategoriesHandler,
        GetManufacturersHandler           $getManufacturersHandler,
        GetProductsHandler                $getProductsHandler,
        GetSuppliersHandler               $getSuppliersHandler,
        CountCarriersHandler              $countCarriersHandler,
        CountProductsHandler              $countProductsHandler

    )
    {
        if (version_compare(_PS_VERSION_, '9.0.0', '<')) {
            parent::__construct();
        }

        $this->module = $module;
        $this->addHandler = $addHandler;
        $this->removeHandler = $removeHandler;
        $this->getCarriersHandler = $getCarriersHandler;
        $this->getCategoriesHandler = $getCategoriesHandler;
        $this->getManufacturersHandler = $getManufacturersHandler;
        $this->getProductsHandler = $getProductsHandler;
        $this->getSuppliersHandler = $getSuppliersHandler;
        $this->countCarriersHandler = $countCarriersHandler;
        $this->countProductsHandler = $countProductsHandler;
    }

    /**
     * @AdminSecurity(
     *     "is_granted('read', request.get('_legacy_controller'))",
     *     redirectRoute="admin_module_manage",
     *     message="Access denied."
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    #[\PrestaShopBundle\Security\Attribute\AdminSecurity(
        "is_granted('read', request.get('_legacy_controller'))",
        redirectRoute: 'admin_module_manage',
        message: 'Access denied.'
    )]
    public function indexAction(Request $request): Response
    {
        $carrierFilterDto = new CarrierFilterDto([]);
        $productFilterDto = new ProductFilterDto([]);
        $allCarriersFilterDto = new CarrierFilterDto(['deleted' => false, 'limit' => null]);
        $carriers = $this->getCarriersHandler->handle(new GetCarriersQuery($carrierFilterDto));
        $allCarriers = $this->getCarriersHandler->handle(new GetCarriersQuery($allCarriersFilterDto));
        $products = $this->getProductsHandler->handle(new GetProductsQuery($productFilterDto));
        $categories = $this->getCategoriesHandler->handle(new GetCategoriesQuery(false));
        $manufacturers = $this->getManufacturersHandler->handle(new GetManufacturersQuery());
        $suppliers = $this->getSuppliersHandler->handle(new GetSuppliersQuery());

        return $this->render(self::TEMPLATE_FOLDER . 'index.html.twig', [
            'enableSidebar' => true,
            'help_link' => $this->generateSidebarLink($request->attributes->get('_legacy_controller')),
            'categories' => $categories,
            'carriers' => $carriers,
            'all_carriers' => $allCarriers,
            'manufacturers' => $manufacturers,
            'products' => $products,
            'suppliers' => $suppliers,
            'module' => $this->module,
            'carrier_filter_data' => $carrierFilterDto->toArray(),
            'carrier_limit' => $carrierFilterDto->limit,
            'carrier_total_pages' => $this->getCarrierTotalPages($carrierFilterDto),
            'carrier_current_page' => $carrierFilterDto->page,
            'product_filter_data' => $productFilterDto->toArray(),
            'product_limit' => $productFilterDto->limit,
            'product_total_pages' => $this->getProductTotalPages($productFilterDto),
            'product_current_page' => $productFilterDto->page,
        ]);
    }

    /**
     * @AdminSecurity(
     *     "is_granted('create', request.get('_legacy_controller'))
     *      or is_granted('delete', request.get('_legacy_controller'))
     *      or is_granted('update', request.get('_legacy_controller'))
     *      or is_granted('read', request.get('_legacy_controller'))",
     *     redirectRoute="admin_drsoft_fr_carrier_product_bulk_index",
     *     message="You do not have permission to edit this."
     * )
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    #[\PrestaShopBundle\Security\Attribute\AdminSecurity(
        "is_granted('create', request.get('_legacy_controller'))
         or is_granted('delete', request.get('_legacy_controller'))
         or is_granted('update', request.get('_legacy_controller'))
         or is_granted('read', request.get('_legacy_controller'))",
        redirectRoute: 'admin_drsoft_fr_carrier_product_bulk_index',
        message: 'You do not have permission to edit this.'
    )]
    public function ajaxAction(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if ($data['action'] === 'add') {
                $this->addHandler->handle(new AddCarriersToProductsCommand($data['carriers'], $data['products']));

                return new JsonResponse([
                    'message' => $this->trans('Carriers added with success!', 'Modules.Drsoftfrcarrierproductbulk.Success'),
                    'type' => 'success'
                ]);
            } elseif ($data['action'] === 'remove') {
                $this->removeHandler->handle(new RemoveCarriersFromProductsCommand($data['carriers'], $data['products']));

                return new JsonResponse([
                    'message' => $this->trans('Transporters successfully removed!', 'Modules.Drsoftfrcarrierproductbulk.Success'),
                    'type' => 'success'
                ]);
            }

            return new JsonResponse([
                'message' => $this->trans('Action not recognized.', 'Modules.Drsoftfrcarrierproductbulk.Error'),
                'type' => 'danger'
            ], 400);
        } catch (\Throwable $t) {
            return new JsonResponse([
                'message' => $this->trans('An error occurred when adding carriers.', 'Modules.Drsoftfrcarrierproductbulk.Error'),
                'type' => 'danger'
            ], 400);
        }
    }

    public function carrierListAction(Request $request): Response
    {
        $filter = new CarrierFilterDto($request->query->all());
        $objs = $this->getCarriersHandler->handle(new GetCarriersQuery($filter));

        return $this->render(self::TEMPLATE_FOLDER . '/partial/_carrier_list.html.twig', [
            'carriers' => $objs,
            'carrier_filter_data' => $filter->toArray(),
            'carrier_limit' => $filter->limit,
            'carrier_total_pages' => $this->getCarrierTotalPages($filter),
            'carrier_current_page' => $filter->page,
        ]);
    }

    public function productListAction(Request $request): Response
    {
        $filter = new ProductFilterDto($request->query->all());
        $objs = $this->getProductsHandler->handle(new GetProductsQuery($filter));

        return $this->render(self::TEMPLATE_FOLDER . '/partial/_product_list.html.twig', [
            'products' => $objs,
            'product_filter_data' => $filter->toArray(),
            'product_limit' => $filter->limit,
            'product_total_pages' => $this->getProductTotalPages($filter),
            'product_current_page' => $filter->page,
        ]);
    }

    private function getCarrierTotalPages(CarrierFilterDto $filter): float
    {
        $total = $this->countCarriersHandler->handle(new CountCarriersQuery($filter));

        return !empty($filter->limit) ? ceil($total / $filter->limit) : 1.0;
    }

    private function getProductTotalPages(ProductFilterDto $filter): float
    {
        $total = $this->countProductsHandler->handle(new CountProductsQuery($filter));

        return ceil($total / $filter->limit);
    }
}
