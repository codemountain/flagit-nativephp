<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Native\Mobile\Facades\SecureStorage;

class ApiAuthService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.api.url'), '/').'/';
    }


    /**
     * GET request
     */
    public function get(string $endpoint, array $query = []): array
    {
        return $this->makeRequest('GET', $endpoint, [], $query);
    }

    /**
     * POST request
     */
    public function post(string $endpoint, array $data = []): array
    {
        return $this->makeRequest('POST', $endpoint, $data);
    }

    public function postMultipart(string $endpoint, array $data = []): array
    {
        return $this->makeRequest('POST', $endpoint, $data, [], true);
    }

    /**
     * PUT request
     */
    public function put(string $endpoint, array $data = []): array
    {
        return $this->makeRequest('PUT', $endpoint, $data);
    }

    /**
     * PATCH request
     */
    public function patch(string $endpoint, array $data = []): array
    {
        return $this->makeRequest('PATCH', $endpoint, $data);
    }

    /**
     * DELETE request
     */
    public function delete(string $endpoint): array
    {
        return $this->makeRequest('DELETE', $endpoint);
    }

    /**
     * Make API request with automatic token handling
     */
    private function makeRequest(string $method, string $endpoint, array $data = [], array $query = [], bool $multipart = false): array
    {
        if ( !SecureStorage::get('api_token') && config('app.env') != 'local') {
            throw new \Exception('User does not have a valid API token');
        }

        $url = $this->baseUrl.ltrim($endpoint, '/');
        $token = SecureStorage::get('api_token');
//        if(config('app.env') == 'local' && SecureStorage::get('api_token')) {
//            $token = SecureStorage::get('api_token');
//        } else {
//            $token = config('services.api.local_token');
//        }


        $contentType = $multipart ? 'multipart/form-data' : 'application/json';
        $request = Http::timeout(20) // 20 second timeout for API requests
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => $contentType,
                'Authorization' => 'Bearer '.$token,
            ]);

        if ($multipart) {
            $request = $request->asMultipart();
        }

        // Add query parameters for GET requests
        if (! empty($query)) {
            $request = $request->withQueryParameters($query);
        }

        // Make the request
        $response = $request->{strtolower($method)}($url, $data);

        // Handle 401 - token might be expired
        if ($response->status() === 401) {
            // For mobile app, just throw exception - user needs to re-login
            throw new \Exception('Authentication token expired. Please log in again.');
        }

        if (! $response->successful()) {
            throw new \Exception('API request failed: '.$response->body());
        }

        return $response->json();
    }

}
