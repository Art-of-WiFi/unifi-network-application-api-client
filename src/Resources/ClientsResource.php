<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Resources;

use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Clients\GetConnectedClientsRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Clients\GetClientDetailsRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Clients\ExecuteClientActionRequest;
use Saloon\Http\Response;

/**
 * Clients Resource
 *
 * Provides access to client management endpoints.
 * Allows viewing and managing connected clients (wired, wireless, VPN, and guest).
 */
class ClientsResource extends BaseResource
{
    /**
     * List all connected clients
     *
     * Retrieves a paginated list of all connected clients on the specified site.
     *
     * @param int|null $page Page number (optional)
     * @param int|null $limit Number of results per page (optional)
     * @param string|null $filter Filter expression (optional)
     * @return Response
     */
    public function list(?int $page = null, ?int $limit = null, ?string $filter = null): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new GetConnectedClientsRequest($siteId, $page, $limit, $filter));
    }

    /**
     * Get client details
     *
     * Retrieves detailed information about a specific connected client.
     *
     * @param string $clientId The client UUID or MAC address
     * @return Response
     */
    public function get(string $clientId): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new GetClientDetailsRequest($siteId, $clientId));
    }

    /**
     * Execute an action on a client
     *
     * Executes an action on a connected client (e.g., authorize, unauthorize guest).
     *
     * @param string $clientId The client UUID or MAC address
     * @param array $action The action payload
     * @return Response
     */
    public function executeAction(string $clientId, array $action): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new ExecuteClientActionRequest($siteId, $clientId, $action));
    }
}
