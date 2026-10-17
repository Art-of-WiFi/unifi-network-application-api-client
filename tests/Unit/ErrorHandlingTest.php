<?php

declare(strict_types=1);

use ArtOfWiFi\UnifiNetworkApplicationApi\UnifiClient;
use Saloon\Exceptions\Request\ClientException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Exceptions\Request\ServerException;
use Saloon\Exceptions\Request\Statuses\ForbiddenException;
use Saloon\Exceptions\Request\Statuses\InternalServerErrorException;
use Saloon\Exceptions\Request\Statuses\NotFoundException;
use Saloon\Exceptions\Request\Statuses\TooManyRequestsException;
use Saloon\Exceptions\Request\Statuses\UnauthorizedException;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

/**
 * Build an error body in the format of the API's "Error Message" schema
 */
function apiError(int $statusCode, string $statusName, string $code, string $message): array
{
    return [
        'statusCode' => $statusCode,
        'statusName' => $statusName,
        'code' => $code,
        'message' => $message,
        'timestamp' => '2026-10-17T12:47:25.544Z',
        'requestPath' => '/integration/v1/sites/88f7af54-98f8-306a-a1c7-c9349722b1f6/devices/550e8400-e29b-41d4-a716-446655440000',
        'requestId' => '3fa85f64-5717-4562-b3fc-2c963f66afa6',
    ];
}

function clientRespondingWith(MockResponse $response, bool $throwOnErrors = false): UnifiClient
{
    $client = new UnifiClient('https://192.168.1.1', 'test-key', throwOnErrors: $throwOnErrors);
    $client->getConnector()->withMockClient(new MockClient([$response]));

    return $client->setSiteId('88f7af54-98f8-306a-a1c7-c9349722b1f6');
}

dataset('error responses', [
    '400 invalid filter' => [400, apiError(400, 'BAD_REQUEST', 'api.request.invalid-filter', 'Invalid filter'), ClientException::class],
    '401 unauthorized (UniFi OS)' => [401, ['error' => ['code' => 401, 'message' => 'Unauthorized']], UnauthorizedException::class],
    '403 forbidden' => [403, apiError(403, 'FORBIDDEN', 'api.authorization.forbidden', 'Forbidden'), ForbiddenException::class],
    '404 not found' => [404, apiError(404, 'NOT_FOUND', 'api.resource-not-found', 'Requested resource does not exist'), NotFoundException::class],
    '429 too many requests' => [429, apiError(429, 'TOO_MANY_REQUESTS', 'api.rate-limit', 'Too many requests'), TooManyRequestsException::class],
    '500 server error' => [500, apiError(500, 'INTERNAL_SERVER_ERROR', 'api.unexpected-error', 'Unexpected error'), InternalServerErrorException::class],
]);

// ===== Default behaviour: failed responses are returned =====

test('failed responses are returned, not thrown, by default', function (int $status, array $body) {
    $response = clientRespondingWith(MockResponse::make($body, $status))->devices()->get('550e8400-e29b-41d4-a716-446655440000');

    expect($response->status())->toBe($status);
    expect($response->failed())->toBeTrue();
    expect($response->successful())->toBeFalse();
    expect($response->json())->toBe($body);
})->with('error responses');

test('throw() on a failed response raises the matching exception', function (int $status, array $body, string $exception) {
    $response = clientRespondingWith(MockResponse::make($body, $status))->devices()->get('550e8400-e29b-41d4-a716-446655440000');

    expect(fn () => $response->throw())->toThrow($exception);
})->with('error responses');

test('the API error details are available on a failed response', function () {
    $response = clientRespondingWith(MockResponse::make(apiError(404, 'NOT_FOUND', 'api.resource-not-found', 'Requested resource does not exist'), 404))
        ->devices()->get('550e8400-e29b-41d4-a716-446655440000');

    expect($response->json('code'))->toBe('api.resource-not-found');
    expect($response->json('message'))->toBe('Requested resource does not exist');
    expect($response->json('requestId'))->toBe('3fa85f64-5717-4562-b3fc-2c963f66afa6');
});

// ===== Opt-in: throw on errors =====

test('failed responses throw when throwOnErrors is enabled in the constructor', function (int $status, array $body, string $exception) {
    $client = clientRespondingWith(MockResponse::make($body, $status), throwOnErrors: true);

    expect(fn () => $client->devices()->get('550e8400-e29b-41d4-a716-446655440000'))->toThrow($exception);
})->with('error responses');

test('4xx responses throw a ClientException and 5xx responses a ServerException', function () {
    $notFound = clientRespondingWith(MockResponse::make([], 404), throwOnErrors: true);
    $serverError = clientRespondingWith(MockResponse::make([], 503), throwOnErrors: true);

    expect(fn () => $notFound->sites()->list())->toThrow(ClientException::class);
    expect(fn () => $serverError->sites()->list())->toThrow(ServerException::class);
});

test('the thrown exception carries the API error response', function () {
    $client = clientRespondingWith(MockResponse::make(apiError(400, 'BAD_REQUEST', 'api.request.invalid-filter', 'Invalid filter'), 400), throwOnErrors: true);

    try {
        $client->networks()->list(filter: "name.eq('x'");
        $this->fail('Expected a RequestException');
    } catch (RequestException $e) {
        expect($e->getResponse()->status())->toBe(400);
        expect($e->getResponse()->json('code'))->toBe('api.request.invalid-filter');
    }
});

test('successful responses do not throw when throwOnErrors is enabled', function () {
    $client = clientRespondingWith(MockResponse::make(['applicationVersion' => '11.0.89']), throwOnErrors: true);

    expect($client->applicationInfo()->get()->json('applicationVersion'))->toBe('11.0.89');
});

test('throwOnErrors() can be toggled on an existing client', function () {
    $client = new UnifiClient('https://192.168.1.1', 'test-key');
    $client->getConnector()->withMockClient(new MockClient([
        MockResponse::make([], 404),
        MockResponse::make([], 404),
    ]));

    expect($client->throwOnErrors())->toBe($client);
    expect($client->getConnector()->isThrowingOnErrors())->toBeTrue();
    expect(fn () => $client->sites()->list())->toThrow(NotFoundException::class);

    $client->throwOnErrors(false);
    expect($client->getConnector()->isThrowingOnErrors())->toBeFalse();
    expect($client->sites()->list()->status())->toBe(404);
});

test('throwOnErrors is disabled by default', function () {
    expect((new UnifiClient('https://192.168.1.1', 'test-key'))->getConnector()->isThrowingOnErrors())->toBeFalse();
});

test('connectViaSiteManager() supports throwOnErrors', function () {
    $client = UnifiClient::connectViaSiteManager('console-id', 'site-manager-key', throwOnErrors: true);
    $client->getConnector()->withMockClient(new MockClient([MockResponse::make(['error' => 'Unauthorized'], 401)]));

    expect($client->getConnector()->isThrowingOnErrors())->toBeTrue();
    expect(fn () => $client->sites()->list())->toThrow(UnauthorizedException::class);
});
