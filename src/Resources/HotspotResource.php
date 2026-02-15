<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Resources;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Hotspot\CreateVouchersRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Hotspot\DeleteVoucherRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Hotspot\DeleteVouchersRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Hotspot\GetVoucherRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Hotspot\GetVouchersRequest;
use RuntimeException;
use Saloon\Exceptions\Request\ClientException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Exceptions\Request\ServerException;
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
     * @param  int|null  $offset  Pagination offset (optional)
     * @param  int|null  $limit  Number of results per page (optional)
     * @param  string|Filter|null  $filter  Filter expression or Filter object (optional)
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (bad request, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function listVouchers(?int $offset = null, ?int $limit = null, string|Filter|null $filter = null): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new GetVouchersRequest($siteId, $offset, $limit, $filter));
    }

    /**
     * Get voucher details
     *
     * Retrieves detailed information about a specific voucher.
     *
     * @param  string  $voucherId  The voucher UUID
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
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
     * @param  array  $data  The voucher configuration data
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (bad request, validation error, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
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
     * @param  string  $voucherId  The voucher UUID
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
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
     * A filter is required by the API to prevent accidental deletion of all vouchers.
     *
     * @param  string  $filter  Filter expression (required)
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (bad request, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function deleteVouchers(string $filter): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new DeleteVouchersRequest($siteId, $filter));
    }
}
