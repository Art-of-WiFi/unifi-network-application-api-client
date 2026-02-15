<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Resources;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\AclRules\CreateAclRuleRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\AclRules\DeleteAclRuleRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\AclRules\GetAclRuleOrderingRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\AclRules\GetAclRuleRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\AclRules\GetAclRulesRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\AclRules\UpdateAclRuleOrderingRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\AclRules\UpdateAclRuleRequest;
use RuntimeException;
use Saloon\Exceptions\Request\ClientException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Exceptions\Request\ServerException;
use Saloon\Http\Response;

/**
 * ACL Rules Resource
 *
 * Provides access to ACL (Access Control List) rule management endpoints.
 * Allows creating, listing, and managing ACL rules that enforce traffic
 * filtering across devices and networks.
 */
class AclRulesResource extends BaseResource
{
    /**
     * List all ACL rules
     *
     * Retrieves a paginated list of all ACL rules on the specified site.
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

        return $this->connector->send(new GetAclRulesRequest($siteId, $offset, $limit, $filter));
    }

    /**
     * Get ACL rule details
     *
     * Retrieves detailed information about a specific ACL rule.
     *
     * @param  string  $ruleId  The ACL rule UUID
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function get(string $ruleId): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new GetAclRuleRequest($siteId, $ruleId));
    }

    /**
     * Create a new ACL rule
     *
     * Creates a new ACL rule on the specified site.
     *
     * @param  array  $data  The ACL rule configuration data
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (bad request, validation error, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function create(array $data): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new CreateAclRuleRequest($siteId, $data));
    }

    /**
     * Update an ACL rule
     *
     * Updates an existing ACL rule configuration.
     *
     * @param  string  $ruleId  The ACL rule UUID
     * @param  array  $data  The updated ACL rule configuration data
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, bad request, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function update(string $ruleId, array $data): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new UpdateAclRuleRequest($siteId, $ruleId, $data));
    }

    /**
     * Delete an ACL rule
     *
     * Deletes an existing ACL rule from the specified site.
     *
     * @param  string  $ruleId  The ACL rule UUID
     * @param  bool  $force  Force deletion (optional)
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, conflict, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function delete(string $ruleId, bool $force = false): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new DeleteAclRuleRequest($siteId, $ruleId, $force));
    }

    // ===== ACL Rule Ordering =====

    /**
     * Get ACL rule ordering
     *
     * Retrieves the current ordering of ACL rules on the specified site.
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (bad request, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function getOrdering(): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new GetAclRuleOrderingRequest($siteId));
    }

    /**
     * Update ACL rule ordering
     *
     * Updates the ordering of ACL rules on the specified site.
     *
     * @param  array  $data  The ordering data (e.g., ['orderedAclRuleIds' => [...]])
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (bad request, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function updateOrdering(array $data): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new UpdateAclRuleOrderingRequest($siteId, $data));
    }
}
