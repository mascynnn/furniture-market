<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * RajaOngkir API Service
 * Docs: https://rajaongkir.com/dokumentasi
 *
 * Set RAJAONGKIR_API_KEY in your .env file.
 * Set RAJAONGKIR_TYPE=starter|basic|pro in .env (default: starter)
 */
class RajaOngkirService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey  = config('rajaongkir.api_key');
        $type          = config('rajaongkir.type', 'starter');
        $this->baseUrl = match ($type) {
            'pro'   => 'https://pro.rajaongkir.com/api',
            'basic' => 'https://rajaongkir.com/api',
            default => 'https://rajaongkir.com/api',
        };
    }

    /**
     * Get list of all provinces.
     */
    public function getProvinces(): array
    {
        return Cache::remember('rajaongkir.provinces', 86400, function () {
            $response = Http::withHeaders(['key' => $this->apiKey])
                ->get("{$this->baseUrl}/province");

            return $response->json('rajaongkir.results', []);
        });
    }

    /**
     * Get cities by province ID.
     */
    public function getCities(string $provinceId): array
    {
        return Cache::remember("rajaongkir.cities.{$provinceId}", 86400, function () use ($provinceId) {
            $response = Http::withHeaders(['key' => $this->apiKey])
                ->get("{$this->baseUrl}/city", ['province' => $provinceId]);

            return $response->json('rajaongkir.results', []);
        });
    }

    /**
     * Calculate shipping cost using RajaOngkir cost API.
     *
     * @param string $origin       Origin city_id
     * @param string $destination  Destination city_id
     * @param int    $weight       Weight in grams
     * @param string $courier      jne|jnt|pos|tiki|anteraja etc.
     * @return array               Array of service options with cost & etd
     */
    public function calculateCost(string $origin, string $destination, int $weight, string $courier): array
    {
        $response = Http::withHeaders(['key' => $this->apiKey])
            ->post("{$this->baseUrl}/cost", [
                'origin'      => $origin,
                'destination' => $destination,
                'weight'      => $weight,
                'courier'     => $courier,
            ]);

        $results = $response->json('rajaongkir.results', []);

        return $results[0]['costs'] ?? [];
    }

    /**
     * Track a shipment via RajaOngkir waybill API.
     * Only available on Basic/Pro plan.
     *
     * @param string $courier  Courier code (jne, pos, tiki...)
     * @param string $resi     Tracking/waybill number
     * @return array|null      Tracking result or null on failure
     */
    public function trackShipment(string $courier, string $resi): ?array
    {
        try {
            $response = Http::withHeaders(['key' => $this->apiKey])
                ->post("{$this->baseUrl}/waybill", [
                    'waybill' => $resi,
                    'courier' => $courier,
                ]);

            $result = $response->json('rajaongkir.result');
            return $result ?: null;

        } catch (\Exception $e) {
            return null;
        }
    }
}
