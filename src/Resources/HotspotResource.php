<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Resources;

use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Hotspot\GetVouchersRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Hotspot\GetVoucherRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Hotspot\CreateVouchersRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Hotspot\DeleteVoucherRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Hotspot\DeleteVouchersRequest;
use Saloon\Http\Response;

/**
 * Hotspot Resource
 *
 * Provides access to hotspot voucher management endpoints.
 * Allows managing guest access via hotspot vouchers - create, list, or revoke vouchers
 * and track their usage and expiration.
 */
class HotspotResource extends BaseResource
{
    /**
     * List all vouchers
     *
     * Retrieves a list of all hotspot vouchers on the specified site.
     *
     * @param int|null $page Page number (optional)
     * @param int|null $limit Number of results per page (optional)
     * @param string|null $filter Filter expression (optional)
     * @return Response
     */
    public function listVouchers(?int $page = null, ?int $limit = null, ?string $filter = null): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new GetVouchersRequest($siteId, $page, $limit, $filter));
    }

    /**
     * Get voucher details
     *
     * Retrieves detailed information about a specific voucher.
     *
     * @param string $voucherId The voucher UUID
     * @return Response
     */
    public function getVoucher(string $voucherId): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new GetVoucherRequest($siteId, $voucherId));
    }

    /**
     * Create vouchers
     *
     * Creates one or more new hotspot vouchers.
     *
     * @param array $data The voucher configuration data
     * @return Response
     */
    public function createVouchers(array $data): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new CreateVouchersRequest($siteId, $data));
    }

    /**
     * Delete a voucher
     *
     * Deletes (revokes) a specific voucher.
     *
     * @param string $voucherId The voucher UUID
     * @return Response
     */
    public function deleteVoucher(string $voucherId): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new DeleteVoucherRequest($siteId, $voucherId));
    }

    /**
     * Delete multiple vouchers
     *
     * Deletes (revokes) multiple vouchers matching a filter.
     *
     * @param string|null $filter Filter expression (optional)
     * @return Response
     */
    public function deleteVouchers(?string $filter = null): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new DeleteVouchersRequest($siteId, $filter));
    }
}
