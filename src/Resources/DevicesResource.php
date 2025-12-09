<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Resources;

use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Devices\GetAdoptedDevicesRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Devices\GetDeviceDetailsRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Devices\GetDeviceStatisticsRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Devices\GetPendingDevicesRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Devices\ExecuteDeviceActionRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Devices\ExecutePortActionRequest;
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
     * @param int|null $page Page number (optional)
     * @param int|null $limit Number of results per page (optional)
     * @param string|null $filter Filter expression (optional)
     * @return Response
     */
    public function listAdopted(?int $page = null, ?int $limit = null, ?string $filter = null): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new GetAdoptedDevicesRequest($siteId, $page, $limit, $filter));
    }

    /**
     * List all pending devices
     *
     * Retrieves a paginated list of all pending (not yet adopted) devices.
     * Note: This endpoint is global and does not require a site ID.
     *
     * @param int|null $page Page number (optional)
     * @param int|null $limit Number of results per page (optional)
     * @param string|null $filter Filter expression (optional)
     * @return Response
     */
    public function listPending(?int $page = null, ?int $limit = null, ?string $filter = null): Response
    {
        return $this->connector->send(new GetPendingDevicesRequest($page, $limit, $filter));
    }

    /**
     * Get device details
     *
     * Retrieves detailed information about a specific device.
     *
     * @param string $deviceId The device UUID
     * @return Response
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
     * @param string $deviceId The device UUID
     * @return Response
     */
    public function getStatistics(string $deviceId): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new GetDeviceStatisticsRequest($siteId, $deviceId));
    }

    /**
     * Execute an action on a device
     *
     * Executes an action on an adopted device (e.g., reboot, locate, upgrade).
     *
     * @param string $deviceId The device UUID
     * @param array $action The action payload
     * @return Response
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
     * @param string $deviceId The device UUID
     * @param int $portIdx The port index (0-based)
     * @param array $action The action payload
     * @return Response
     */
    public function executePortAction(string $deviceId, int $portIdx, array $action): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new ExecutePortActionRequest($siteId, $deviceId, $portIdx, $action));
    }
}
