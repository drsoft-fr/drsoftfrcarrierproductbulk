<?php

declare(strict_types=1);

use DrSoftFr\Module\CarrierProductBulk\UI\Admin\Controller\CarrierProductBulkController;
use PrestaShop\PrestaShop\Core\Cache\Clearer\CacheClearerChain;

if (!defined('_PS_VERSION_')) {
    exit;
}

$autoloadPath = __DIR__ . '/vendor/autoload.php';

if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}

/**
 * Class drsoftfrcarrierproductbulk
 */
class drsoftfrcarrierproductbulk extends Module
{
    const ERROR_MESSAGE_PATTERN = 'drsoftfrcarrierproductbulk - %s - %d - Throwable #%d - %s.';

    /**
     * @var string $authorEmail Author email
     */
    public $authorEmail;

    /**
     * @var string $moduleGithubRepositoryUrl Module GitHub repository URL
     */
    public $moduleGithubRepositoryUrl;

    /**
     * @var string $moduleGithubIssuesUrl Module GitHub issues URL
     */
    public $moduleGithubIssuesUrl;

    public function __construct()
    {
        $this->author = 'drSoft.fr';
        $this->bootstrap = true;
        $this->dependencies = [];
        $this->name = 'drsoftfrcarrierproductbulk';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '8.0.0',
            'max' => _PS_VERSION_
        ];
        $this->tab = 'content_management';
        $this->tabs = [
            [
                'class_name' => CarrierProductBulkController::TAB_CLASS_NAME,
                'name' => 'Carrier Product Bulk',
                'parent_class_name' => 'AdminParentShipping',
                'route_name' => 'admin_drsoft_fr_carrier_product_bulk_index',
                'visible' => true,
            ],
        ];
        $this->version = '1.1.1';
        $this->authorEmail = 'contact@drsoft.fr';
        $this->moduleGithubRepositoryUrl = 'https://github.com/drsoft-fr/drsoftfrcarrierproductbulk';
        $this->moduleGithubIssuesUrl = 'https://github.com/drsoft-fr/drsoftfrcarrierproductbulk/issues';

        parent::__construct();

        $this->displayName = $this->trans('drSoft.fr Carrier Product Bulk', [], 'Modules.Drsoftfrcarrierproductbulk.Admin');
        $this->description = $this->trans('Select or deselect carriers by product batch.', [], 'Modules.Drsoftfrcarrierproductbulk.Admin');

        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall?', [], 'Modules.Drsoftfrcarrierproductbulk.Admin');
    }

    /**
     * Disables the module.
     *
     * @param bool $force_all Whether to disable all instances of the module, even if they are currently enabled.
     *
     * @return bool Whether the module was disabled successfully.
     */
    public function disable($force_all = false)
    {
        if (!parent::disable($force_all)) {
            $this->handleException(
                new Exception(
                    $this->trans(
                        'An error has occurred when deactivating the module.',
                        [],
                        'Modules.Drsoftfrcarrierproductbulk.Error'
                    )
                ),
                __METHOD__,
                __LINE__
            );

            return false;
        }

        try {
            $this->getCacheClearerChain()->clear();
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return true;
    }

    /**
     * Enables the module by clearing the cache and calling the parent's enable method.
     *
     * @param bool $force_all Whether to force the enabling of all modules.
     *
     * @return bool True on successful enabling, false otherwise.
     */
    public function enable($force_all = false)
    {
        if (!parent::enable($force_all)) {
            $this->handleException(
                new Exception(
                    $this->trans(
                        'An error has occurred when activating the module.',
                        [],
                        'Modules.Drsoftfrcarrierproductbulk.Error'
                    )
                ),
                __METHOD__,
                __LINE__
            );

            return false;
        }

        try {
            $this->getCacheClearerChain()->clear();
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return true;
    }

    /**
     * Get the CacheClearerChain.
     *
     * @return CacheClearerChain
     *
     * @throws Exception
     */
    private function getCacheClearerChain(): CacheClearerChain
    {
        $cacheClearerChain = $this->get('prestashop.core.cache.clearer.cache_clearer_chain');

        if (!($cacheClearerChain instanceof CacheClearerChain)) {
            throw new Exception('The cacheClearerChain object must implement CacheClearerChain.');
        }

        return $cacheClearerChain;
    }

    /**
     * Redirects the admin user to the ValidateCustomerPro controller in the admin panel.
     *
     * @return void
     */
    public function getContent(): void
    {
        Tools::redirectAdmin(
            $this->context->link->getAdminLink(CarrierProductBulkController::TAB_CLASS_NAME)
        );
    }

    /**
     * Handles an exception by logging an error message.
     *
     * @param Throwable $t The exception to handle.
     * @param string $method The name of the method where the exception occurred. Defaults to the current method name.
     * @param int $line The line number where the exception occurred. Defaults to the current line number.
     *
     * @return void
     */
    private function handleException(Throwable $t, string $method = __METHOD__, int $line = __LINE__): void
    {
        $errorMessage = sprintf(self::ERROR_MESSAGE_PATTERN, $method, $line, $t->getCode(), $t->getMessage());

        PrestaShopLogger::addLog($errorMessage, 3);

        $this->_errors[] = $errorMessage;
    }

    /**
     * Installs the module
     *
     * @return bool Returns true if the installation is successful, false otherwise.
     *
     * @throws PrestaShopException
     */
    public function install(): bool
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if (!parent::install()) {
            $this->handleException(
                new Exception(
                    $this->trans(
                        'There was an error during the installation.',
                        [],
                        'Modules.Drsoftfrcarrierproductbulk.Error'
                    )
                ),
                __METHOD__,
                __LINE__
            );

            return false;
        }

        try {
            $this->getCacheClearerChain()->clear();
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isUsingNewTranslationSystem(): bool
    {
        return true;
    }

    /**
     * Uninstalls the module
     *
     * @return bool Returns true if uninstallation was successful, false otherwise
     */
    public function uninstall(): bool
    {
        if (!parent::uninstall()) {
            $this->handleException(
                new Exception(
                    $this->trans(
                        'There was an error during the uninstallation.',
                        [],
                        'Modules.Drsoftfrcarrierproductbulk.Error'
                    )
                ),
                __METHOD__,
                __LINE__
            );

            return false;
        }

        try {
            $this->getCacheClearerChain()->clear();
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return true;
    }
}
