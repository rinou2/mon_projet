<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class DeliveryCalculatorService
{
    private HttpClientInterface $client;
    private const BORDEAUX_LAT = 44.837789;
    private const BORDEAUX_LON = -0.579180;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function calculateDeliveryCost(string $address, string $city): float
    {
        if (mb_strtolower(trim($city)) === 'bordeaux') {
            return 5.0;
        }
        $coords = $this->getCoordinates($address . ', ' . $city);

        if (!$coords) {
            return 5.0; 
        }

        $distance = $this->calculateDistance(
            self::BORDEAUX_LAT, 
            self::BORDEAUX_LON, 
            $coords['latitude'], 
            $coords['longitude']
        );

        $cost = 5.0 + ($distance * 0.59);
        return round($cost, 2);
    }

    private function getCoordinates(string $fullAddress): ?array
    {
        try {
            $response = $this->client->request('GET', 'https://nominatim.openstreetmap.org/search', [
                'query' => [
                    'q' => $fullAddress,
                    'format' => 'json',
                    'limit' => 1,
                ],
                'headers' => [
                    'User-Agent' => 'MonAppCommande/1.0',
                ],
            ]);

            $data = $response->toArray();

            if (empty($data)) {
                return null;
            }

            return [
                'latitude' => (float) $data[0]['lat'],
                'longitude' => (float) $data[0]['lon'],
            ];
        } catch (\Exception $e) {
            return null;
        }
    }

    private function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}