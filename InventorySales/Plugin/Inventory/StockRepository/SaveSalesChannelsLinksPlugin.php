<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\InventorySales\Plugin\Inventory\StockRepository;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\InventoryApi\Api\Data\StockInterface;
use Magento\InventoryApi\Api\StockRepositoryInterface;
use Magento\InventorySales\Model\ReplaceSalesChannelsForStockInterface;
use Psr\Log\LoggerInterface;

/**
 * Save Sales Channels Links for Stock on Save method of StockRepositoryInterface
 */
class SaveSalesChannelsLinksPlugin
{
    /**
     * @var ReplaceSalesChannelsForStockInterface
     */
    private $replaceSalesChannelsOnStock;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param ReplaceSalesChannelsForStockInterface $replaceSalesChannelsOnStock
     * @param LoggerInterface $logger
     */
    public function __construct(
        ReplaceSalesChannelsForStockInterface $replaceSalesChannelsOnStock,
        LoggerInterface $logger
    ) {
        $this->replaceSalesChannelsOnStock = $replaceSalesChannelsOnStock;
        $this->logger = $logger;
    }

    /**
     * Saves Sales Channel Link for Stock
     *
     * @param StockRepositoryInterface $subject
     * @param callable $proceed
     * @param StockInterface $stock
     * @return int
     * @throws CouldNotSaveException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundSave(
        StockRepositoryInterface $subject,
        callable $proceed,
        StockInterface $stock
    ): int {
        try {
            return $this->doAroundSave($proceed, $stock);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw new CouldNotSaveException(__('Could not save Sales Channels Link for Stock'), $e);
        }
    }

    /**
     * @param callable $proceed
     * @param StockInterface $stock
     * @return int
     */
    private function doAroundSave(callable $proceed, StockInterface $stock)
    {
        $extensionAttributes = $stock->getExtensionAttributes();
        $salesChannels = $extensionAttributes->getSalesChannels();
        $stockId = $proceed($stock);
        if (null !== $salesChannels) {
            $this->replaceSalesChannelsOnStock->execute($salesChannels, $stockId);
        }
        return $stockId;
    }
}
