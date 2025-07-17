<?php

namespace App\Traits;

use App\Models\BusinessSetting;
use Illuminate\Support\Facades\Http;

trait EasyPercelEngineTrait
{
    protected static ?string $apiKey = null;
    protected static ?string $baseUrl = null;
    private static bool $initialized = false;

    // Initialize once
    private static function initialize(): void
    {
        if (self::$initialized) {
            return;
        }

        self::$baseUrl = BusinessSetting::where('key', 'easy_parcel_api_url')->first()?->value
            ?? 'https://demo.connect.easyparcel.my';

        self::$apiKey = BusinessSetting::where('key', 'easy_parcel_api_key')->first()?->value;

        self::$initialized = true;
    }

    public static function rateCheckEngine(array $data): array
    {
        self::initialize();

        if ($error = self::failIfNotConfigured()) {
            return $error;
        }

        $endpoint = self::$baseUrl . '?ac=EPRateCheckingBulk';

        $payload = [
            'api' => self::$apiKey,
            'bulk' => [$data],
            'exclude_fields' => [
                'rates.*.dropoff_point',
                'rates.*.pickup_point'
            ]
        ];

        return self::makeHttpsRequest($endpoint, $payload);
    }

    public static function orderSubmitEngine(array $data): array
    {
        self::initialize();

        if ($error = self::failIfNotConfigured()) {
            return $error;
        }

        $endpoint = self::$baseUrl . '?ac=EPSubmitOrderBulk';

        $payload = [
            'api' => self::$apiKey,
            'bulk' => [$data],
        ];

        return self::makeHttpsRequest($endpoint, $payload);
    }

    public static function payEngine(array $data): array
    { // This function is only for the sandbox environment
        self::initialize();

        if ($error = self::failIfNotConfigured()) {
            return $error;
        }

        $endpoint = self::$baseUrl . '?ac=EPPayOrderBulk';

        $payload = [
            'api' => self::$apiKey,
            'bulk' => [$data],
        ];

        return self::makeHttpsRequest($endpoint, $payload);
    }


    public static function orderStatusEngine(array $data): array
    {
        self::initialize();

        if ($error = self::failIfNotConfigured()) {
            return $error;
        }

        $endpoint = self::$baseUrl . '?ac=EPOrderStatusBulk';

        $payload = [
            'api' => self::$apiKey,
            'bulk' => [$data],
        ];

        return self::makeHttpsRequest($endpoint, $payload);
    }


    public static function trackingEngine(array $data): array
    {
        self::initialize();

        if ($error = self::failIfNotConfigured()) {
            return $error;
        }

        $endpoint = self::$baseUrl . '?ac=EPTrackingBulk';

        $payload = [
            'api' => self::$apiKey,
            'bulk' => [$data],
        ];

        return self::makeHttpsRequest($endpoint, $payload);
    }




    private static function makeHttpsRequest(string $url, array $data, array $headers = []): array
    {
        self::initialize();

        if ($error = self::failIfNotConfigured()) {
            return $error;
        }

        $defaultHeaders = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        try {
            $response = Http::withHeaders(array_merge($defaultHeaders, $headers))
                ->timeout(200)
                ->post($url, $data);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'status' => $response->status(),
                'message' => $response->body(),
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    private static function isConfigured(): bool
    {
        return filled(self::$apiKey) && filled(self::$baseUrl);
    }

    private static function failIfNotConfigured(): ?array
    {
        if (!self::isConfigured()) {
            return [
                'success' => false,
                'message' => 'EasyParcel API credentials are not initialized.',
            ];
        }

        return null;
    }
}
