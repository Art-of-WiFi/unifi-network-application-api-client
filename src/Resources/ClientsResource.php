<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Resources;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Clients\ExecuteClientActionRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Clients\GetClientDetailsRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Clients\GetConnectedClientsRequest;
use RuntimeException;
use Saloon\Exceptions\Request\ClientException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Exceptions\Request\ServerException;
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
     * @param  int|null  $offset  Pagination offset (optional)
     * @param  int|null  $limit  Number of results per page (optional)
     * @param  string|Filter|null  $filter  Filter expression or Filter object (optional)
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (bad request, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function list(?int $offset = null, ?int $limit = null, string|Filter|null $filter = null): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new GetConnectedClientsRequest($siteId, $offset, $limit, $filter));
    }

    /**
     * Get client details
     *
     * Retrieves detailed information about a specific connected client.
     *
     * @param  string  $clientId  The client UUID or MAC address
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function get(string $clientId): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new GetClientDetailsRequest($siteId, $clientId));
    }

    /**
     * Execute an action on a client
     *
     * Executes an action on a connected client. According to the API specification,
     * the only officially documented actions are AUTHORIZE_GUEST_ACCESS and
     * UNAUTHORIZE_GUEST_ACCESS.
     *
     * @param  string  $clientId  The client UUID or MAC address
     * @param  array  $action  The action payload (e.g., ['action' => 'AUTHORIZE_GUEST_ACCESS', 'timeLimitMinutes' => 480])
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, bad request, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function executeAction(string $clientId, array $action): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new ExecuteClientActionRequest($siteId, $clientId, $action));
    }
}
