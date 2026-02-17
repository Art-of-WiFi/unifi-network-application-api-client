<?php

/**
 * Example 10: Firewall Policies, ACL Rule Ordering, and DNS Policies
 *
 * This example demonstrates how to work with firewall policies (CRUD + ordering),
 * ACL rule ordering, and DNS policies (CRUD) including the use of filter builders.
 */

require_once __DIR__.'/../vendor/autoload.php';

use ArtOfWiFi\UnifiNetworkApplicationApi\UnifiClient;

$config = require_once __DIR__.'/config.php';

// Configuration - Update the values in the config.php file
$controllerUrl = $config['base_url'];
$apiKey = $config['api_key'];
$siteId = $config['site_id'];
$verifySsl = $config['verify_ssl'];

try {
    // Initialize the API client
    $apiClient = new UnifiClient($controllerUrl, $apiKey, $verifySsl);
    $apiClient->setSiteId($siteId);

    echo "UniFi API Client - Firewall Policies, ACL Ordering & DNS Policies Example\n";
    echo str_repeat('=', 70)."\n\n";

    // ========================================================================
    // PART 1: FIREWALL POLICIES
    // ========================================================================
    echo "PART 1: FIREWALL POLICIES\n";
    echo str_repeat('-', 50)."\n\n";

    // 1. List all firewall policies
    echo "1. Listing all firewall policies...\n";
    $policiesResponse = $apiClient->firewall()->listPolicies();
    $policies = $policiesResponse->json();

    if (isset($policies['data']) && count($policies['data']) > 0) {
        echo '   Total policies: '.($policies['totalCount'] ?? count($policies['data']))."\n";
        foreach ($policies['data'] as $policy) {
            $name = $policy['name'] ?? 'Unnamed';
            $id = $policy['id'] ?? 'N/A';
            $enabled = ($policy['enabled'] ?? false) ? 'Enabled' : 'Disabled';
            $action = $policy['action'] ?? 'N/A';

            echo "   - {$name} (ID: {$id})\n";
            echo "     Action: {$action}, Status: {$enabled}\n";
        }

        // Use the first policy for detailed example
        $firstPolicy = $policies['data'][0];
        $policyId = $firstPolicy['id'];
        $policyName = $firstPolicy['name'] ?? 'Unnamed';

        // 2. Get details for a specific firewall policy
        echo "\n2. Getting details for policy: {$policyName}\n";
        $detailResponse = $apiClient->firewall()->getPolicy($policyId);
        $detail = $detailResponse->json();

        echo '   Name: '.($detail['name'] ?? 'N/A')."\n";
        echo '   Action: '.($detail['action'] ?? 'N/A')."\n";
        echo '   Enabled: '.(($detail['enabled'] ?? false) ? 'Yes' : 'No')."\n";
        echo "\n";
    } else {
        echo "   No firewall policies found.\n\n";
    }

    // 3. List firewall policies with filter builder
    echo "3. Filtering firewall policies:\n";
    echo "   Using FirewallPolicyFilter:\n";
    echo "   \$apiClient->firewall()->listPolicies(\n";
    echo "       filter: FirewallPolicyFilter::name()->like('Block*')\n";
    echo "   );\n\n";
    echo "   Filter by source zone:\n";
    echo "   \$apiClient->firewall()->listPolicies(\n";
    echo "       filter: FirewallPolicyFilter::sourceZoneId()->equals('zone-uuid')\n";
    echo "   );\n\n";
    echo "   Combine source and destination zone:\n";
    echo "   \$apiClient->firewall()->listPolicies(\n";
    echo "       filter: FirewallPolicyFilter::and(\n";
    echo "           FirewallPolicyFilter::sourceZoneId()->equals('zone-1-uuid'),\n";
    echo "           FirewallPolicyFilter::destinationZoneId()->equals('zone-2-uuid')\n";
    echo "       )\n";
    echo "   );\n\n";

    // 4. Create a firewall policy (commented out for safety)
    echo "4. Creating a Firewall Policy (example - commented out for safety)\n";
    echo "   \$apiClient->firewall()->createPolicy([\n";
    echo "       'name' => 'Block IoT to LAN',\n";
    echo "       'enabled' => true,\n";
    echo "       'action' => 'BLOCK',\n";
    echo "       'source' => ['zoneId' => 'iot-zone-uuid'],\n";
    echo "       'destination' => ['zoneId' => 'lan-zone-uuid'],\n";
    echo "   ]);\n\n";

    // Uncomment to actually create a policy:
    // $createResponse = $apiClient->firewall()->createPolicy([
    //     'name' => 'Block IoT to LAN',
    //     'enabled' => true,
    //     'action' => 'BLOCK',
    //     'source' => ['zoneId' => 'iot-zone-uuid'],
    //     'destination' => ['zoneId' => 'lan-zone-uuid'],
    // ]);

    // 5. Update a firewall policy (commented out for safety)
    echo "5. Updating a Firewall Policy (example - commented out for safety)\n";
    echo "   Full update (PUT):\n";
    echo "   \$apiClient->firewall()->updatePolicy(\$policyId, [\n";
    echo "       'name' => 'Updated Policy Name',\n";
    echo "       'enabled' => true,\n";
    echo "       'action' => 'ALLOW',\n";
    echo "       // ... all required fields\n";
    echo "   ]);\n\n";
    echo "   Partial update (PATCH - only changed fields):\n";
    echo "   \$apiClient->firewall()->patchPolicy(\$policyId, [\n";
    echo "       'enabled' => false\n";
    echo "   ]);\n\n";

    // Uncomment to actually update:
    // $apiClient->firewall()->patchPolicy($policyId, ['enabled' => false]);

    // 6. Delete a firewall policy (commented out for safety)
    echo "6. Deleting a Firewall Policy (example - commented out for safety)\n";
    echo "   \$apiClient->firewall()->deletePolicy(\$policyId);\n\n";

    // Uncomment to actually delete:
    // $apiClient->firewall()->deletePolicy($policyId);

    // 7. Firewall policy ordering
    echo "7. Firewall Policy Ordering\n";
    echo "   Get ordering between two zones:\n";
    echo "   \$ordering = \$apiClient->firewall()->getPolicyOrdering(\n";
    echo "       sourceFirewallZoneId: 'source-zone-uuid',\n";
    echo "       destinationFirewallZoneId: 'destination-zone-uuid'\n";
    echo "   );\n\n";
    echo "   Update ordering (commented out for safety):\n";
    echo "   \$apiClient->firewall()->updatePolicyOrdering(\n";
    echo "       sourceFirewallZoneId: 'source-zone-uuid',\n";
    echo "       destinationFirewallZoneId: 'destination-zone-uuid',\n";
    echo "       data: ['orderedFirewallPolicyIds' => ['policy-1', 'policy-2']]\n";
    echo "   );\n\n";

    // ========================================================================
    // PART 2: ACL RULE ORDERING
    // ========================================================================
    echo "PART 2: ACL RULE ORDERING\n";
    echo str_repeat('-', 50)."\n\n";

    // 8. Get ACL rule ordering
    echo "8. Getting ACL rule ordering...\n";
    $orderingResponse = $apiClient->aclRules()->getOrdering();
    $ordering = $orderingResponse->json();

    echo "   Current ordering retrieved successfully.\n";
    if (isset($ordering['orderedAclRuleIds'])) {
        $ruleCount = count($ordering['orderedAclRuleIds']);
        echo "   Number of ordered rules: {$ruleCount}\n";
    }
    echo "\n";

    // 9. Update ACL rule ordering (commented out for safety)
    echo "9. Updating ACL Rule Ordering (example - commented out for safety)\n";
    echo "   \$apiClient->aclRules()->updateOrdering([\n";
    echo "       'orderedAclRuleIds' => ['rule-1-uuid', 'rule-2-uuid', 'rule-3-uuid']\n";
    echo "   ]);\n\n";

    // Uncomment to actually update ordering:
    // $apiClient->aclRules()->updateOrdering([
    //     'orderedAclRuleIds' => ['rule-1-uuid', 'rule-2-uuid', 'rule-3-uuid']
    // ]);

    // ========================================================================
    // PART 3: DNS POLICIES
    // ========================================================================
    echo "PART 3: DNS POLICIES\n";
    echo str_repeat('-', 50)."\n\n";

    // 10. List all DNS policies
    echo "10. Listing all DNS policies...\n";
    $dnsPoliciesResponse = $apiClient->dnsPolicies()->list();
    $dnsPolicies = $dnsPoliciesResponse->json();

    if (isset($dnsPolicies['data']) && count($dnsPolicies['data']) > 0) {
        echo '    Total DNS policies: '.($dnsPolicies['totalCount'] ?? count($dnsPolicies['data']))."\n";
        foreach ($dnsPolicies['data'] as $policy) {
            $type = $policy['type'] ?? 'Unknown';
            $domain = $policy['domain'] ?? 'N/A';
            $enabled = ($policy['enabled'] ?? false) ? 'Enabled' : 'Disabled';
            $id = $policy['id'] ?? 'N/A';

            echo "    - [{$type}] {$domain} ({$enabled})\n";
            echo "      ID: {$id}\n";
        }
        echo "\n";

        // Use the first DNS policy for detailed example
        $firstDnsPolicy = $dnsPolicies['data'][0];
        $dnsPolicyId = $firstDnsPolicy['id'];

        // 11. Get details for a specific DNS policy
        echo "11. Getting details for DNS policy: {$dnsPolicyId}\n";
        $dnsDetailResponse = $apiClient->dnsPolicies()->get($dnsPolicyId);
        $dnsDetail = $dnsDetailResponse->json();

        echo '    Type: '.($dnsDetail['type'] ?? 'N/A')."\n";
        echo '    Domain: '.($dnsDetail['domain'] ?? 'N/A')."\n";
        echo '    Enabled: '.(($dnsDetail['enabled'] ?? false) ? 'Yes' : 'No')."\n";
        echo '    TTL: '.($dnsDetail['ttlSeconds'] ?? 'N/A')." seconds\n";
        echo "\n";
    } else {
        echo "    No DNS policies found.\n\n";
    }

    // 12. DNS policy filter examples
    echo "12. Filtering DNS policies:\n";
    echo "    Using DnsPolicyFilter preset - find A records:\n";
    echo "    \$apiClient->dnsPolicies()->list(\n";
    echo "        filter: DnsPolicyFilter::aRecords()\n";
    echo "    );\n\n";
    echo "    Find enabled CNAME records:\n";
    echo "    \$apiClient->dnsPolicies()->list(\n";
    echo "        filter: DnsPolicyFilter::and(\n";
    echo "            DnsPolicyFilter::cnameRecords(),\n";
    echo "            DnsPolicyFilter::enabledOnly()\n";
    echo "        )\n";
    echo "    );\n\n";
    echo "    Find policies for a specific domain:\n";
    echo "    \$apiClient->dnsPolicies()->list(\n";
    echo "        filter: DnsPolicyFilter::domain()->like('*.example.com')\n";
    echo "    );\n\n";
    echo "    Find MX records with high priority:\n";
    echo "    \$apiClient->dnsPolicies()->list(\n";
    echo "        filter: DnsPolicyFilter::and(\n";
    echo "            DnsPolicyFilter::mxRecords(),\n";
    echo "            DnsPolicyFilter::priority()->lessThanOrEqual(10)\n";
    echo "        )\n";
    echo "    );\n\n";

    echo "    Available presets: aRecords(), aaaaRecords(), cnameRecords(), mxRecords(),\n";
    echo "    txtRecords(), srvRecords(), forwardDomains(), enabledOnly()\n\n";

    // 13. Create a DNS policy (commented out for safety)
    echo "13. Creating DNS Policies (examples - commented out for safety)\n";
    echo "    A record:\n";
    echo "    \$apiClient->dnsPolicies()->create([\n";
    echo "        'type' => 'A',\n";
    echo "        'enabled' => true,\n";
    echo "        'domain' => 'myapp.local',\n";
    echo "        'ipv4Address' => '192.168.1.100',\n";
    echo "        'ttlSeconds' => 3600,\n";
    echo "    ]);\n\n";
    echo "    CNAME record:\n";
    echo "    \$apiClient->dnsPolicies()->create([\n";
    echo "        'type' => 'CNAME',\n";
    echo "        'enabled' => true,\n";
    echo "        'domain' => 'alias.local',\n";
    echo "        'targetDomain' => 'myapp.local',\n";
    echo "        'ttlSeconds' => 3600,\n";
    echo "    ]);\n\n";
    echo "    MX record:\n";
    echo "    \$apiClient->dnsPolicies()->create([\n";
    echo "        'type' => 'MX',\n";
    echo "        'enabled' => true,\n";
    echo "        'domain' => 'example.com',\n";
    echo "        'mailServerDomain' => 'mail.example.com',\n";
    echo "        'priority' => 10,\n";
    echo "        'ttlSeconds' => 3600,\n";
    echo "    ]);\n\n";

    // Uncomment to actually create:
    // $createResponse = $apiClient->dnsPolicies()->create([
    //     'type' => 'A',
    //     'enabled' => true,
    //     'domain' => 'myapp.local',
    //     'ipv4Address' => '192.168.1.100',
    //     'ttlSeconds' => 3600,
    // ]);

    // 14. Update a DNS policy (commented out for safety)
    echo "14. Updating a DNS Policy (example - commented out for safety)\n";
    echo "    \$apiClient->dnsPolicies()->update(\$dnsPolicyId, [\n";
    echo "        'enabled' => false,\n";
    echo "        'ipv4Address' => '192.168.1.200',\n";
    echo "    ]);\n\n";

    // Uncomment to actually update:
    // $apiClient->dnsPolicies()->update($dnsPolicyId, [
    //     'enabled' => false,
    //     'ipv4Address' => '192.168.1.200',
    // ]);

    // 15. Delete a DNS policy (commented out for safety)
    echo "15. Deleting a DNS Policy (example - commented out for safety)\n";
    echo "    \$apiClient->dnsPolicies()->delete(\$dnsPolicyId);\n\n";

    // Uncomment to actually delete:
    // $apiClient->dnsPolicies()->delete($dnsPolicyId);

    echo str_repeat('=', 70)."\n";
    echo "Example completed successfully!\n";
} catch (Exception $e) {
    echo 'ERROR: '.$e->getMessage()."\n";
}
