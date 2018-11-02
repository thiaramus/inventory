<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\InventorySourceSelectionApi\Api;

/**
 * Service returns Default distance provider Code
 *
 * @api
 */
interface GetDistanceProviderCodeInterface
{
    /**
     * Get Default distance provider code
     *
     * @return string
     */
    public function execute(): string;
}
