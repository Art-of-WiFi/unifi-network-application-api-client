<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Resources;

use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\AclRules\GetAclRulesRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\AclRules\GetAclRuleRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\AclRules\CreateAclRuleRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\AclRules\UpdateAclRuleRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\AclRules\DeleteAclRuleRequest;
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
     * @param int|null $page Page number (optional)
     * @param int|null $limit Number of results per page (optional)
     * @param string|null $filter Filter expression (optional)
     * @return Response
     */
    public function list(?int $page = null, ?int $limit = null, ?string $filter = null): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new GetAclRulesRequest($siteId, $page, $limit, $filter));
    }

    /**
     * Get ACL rule details
     *
     * Retrieves detailed information about a specific ACL rule.
     *
     * @param string $ruleId The ACL rule UUID
     * @return Response
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
     * @param array $data The ACL rule configuration data
     * @return Response
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
     * @param string $ruleId The ACL rule UUID
     * @param array $data The updated ACL rule configuration data
     * @return Response
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
     * @param string $ruleId The ACL rule UUID
     * @param bool $force Force deletion (optional)
     * @return Response
     */
    public function delete(string $ruleId, bool $force = false): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new DeleteAclRuleRequest($siteId, $ruleId, $force));
    }
}
