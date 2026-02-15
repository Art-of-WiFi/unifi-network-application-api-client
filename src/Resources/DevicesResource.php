<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Resources;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Devices\AdoptDeviceRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Devices\ExecuteDeviceActionRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Devices\ExecutePortActionRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Devices\GetAdoptedDevicesRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Devices\GetDeviceDetailsRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Devices\GetDeviceStatisticsRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Devices\GetPendingDevicesRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Devices\RemoveDeviceRequest;
use RuntimeException;
use Saloon\Exceptions\Request\ClientException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Exceptions\Request\ServerException;
use Saloon\Http\Response;

/**
 * Devices Resource
 *
 * Provides access to UniFi device management endpoints.
 * Allows you to list, inspect, and interact with UniFi devices.
 */
class DevicesResource extends BaseResource
{
    /**
     * List all adopted devices
     *
     * Retrieves a paginated list of all adopted (online) devices on the specified site.
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
    public function listAdopted(?int $offset = null, ?int $limit = null, string|Filter|null $filter = null): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new GetAdoptedDevicesRequest($siteId, $offset, $limit, $filter));
    }

    /**
     * List all pending devices
     *
     * Retrieves a paginated list of all pending (not yet adopted) devices.
     * Note: This endpoint is global and does not require a site ID.
     *
     * @param  int|null  $offset  Pagination offset (optional, default: 0)
     * @param  int|null  $limit  Number of results per page (optional)
     * @param  string|null  $filter  Filter expression (optional)
     *
     * @throws ClientException If the request fails with a 4xx error (bad request, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function listPending(?int $offset = null, ?int $limit = null, ?string $filter = null): Response
    {
        return $this->connector->send(new GetPendingDevicesRequest($offset, $limit, $filter));
    }

    /**
     * Get device details
     *
     * Retrieves detailed information about a specific device.
     *
     * @param  string  $deviceId  The device UUID
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function get(string $deviceId): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new GetDeviceDetailsRequest($siteId, $deviceId));
    }

    /**
     * Get device statistics
     *
     * Retrieves the latest statistics for a specific device.
     *
     * @param  string  $deviceId  The device UUID
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function getStatistics(string $deviceId): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new GetDeviceStatisticsRequest($siteId, $deviceId));
    }

    /**
     * Execute an action on a device
     *
     * Executes an action on an adopted device. According to the API specification,
     * the only officially documented action is RESTART.
     *
     * @param  string  $deviceId  The device UUID
     * @param  array  $action  The action payload (e.g., ['action' => 'RESTART'])
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, bad request, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function executeAction(string $deviceId, array $action): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new ExecuteDeviceActionRequest($siteId, $deviceId, $action));
    }

    /**
     * Execute an action on a device port
     *
     * Executes an action on a specific port of a device (e.g., enable, disable, PoE control).
     *
     * @param  string  $deviceId  The device UUID
     * @param  int  $portIdx  The port index (0-based)
     * @param  array  $action  The action payload
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, bad request, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function executePortAction(string $deviceId, int $portIdx, array $action): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new ExecutePortActionRequest($siteId, $deviceId, $portIdx, $action));
    }

    /**
     * Adopt a device
     *
     * Adopts a pending device to the specified site using its MAC address.
     *
     * @param  string  $macAddress  The MAC address of the device to adopt
     * @param  bool  $ignoreDeviceLimit  Whether to ignore the device limit for the site (optional)
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (bad request, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function adopt(string $macAddress, bool $ignoreDeviceLimit = false): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new AdoptDeviceRequest($siteId, $macAddress, $ignoreDeviceLimit));
    }

    /**
     * Remove (unadopt) a device
     *
     * Removes an adopted device from the specified site.
     *
     * @param  string  $deviceId  The device UUID
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function remove(string $deviceId): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new RemoveDeviceRequest($siteId, $deviceId));
    }
}
