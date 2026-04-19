<?php

return [
    /*
    |--------------------------------------------------------------------------
    | RajaOngkir API Key
    |--------------------------------------------------------------------------
    | Daftarkan di https://rajaongkir.com dan masukkan API key Anda di .env:
    |   RAJAONGKIR_API_KEY=your_api_key_here
    |   RAJAONGKIR_TYPE=starter   (starter | basic | pro)
    |   RAJAONGKIR_ORIGIN_CITY=109   (ID kota asal toko — 109 = Bandar Lampung)
    */
    'api_key'        => env('RAJAONGKIR_API_KEY', ''),
    'type'           => env('RAJAONGKIR_TYPE', 'starter'),

    /*
    |--------------------------------------------------------------------------
    | Origin City ID (kota asal pengiriman toko Anda)
    |--------------------------------------------------------------------------
    | Cek ID kota di: https://rajaongkir.com/dokumentasi/starter#city
    */
    'origin_city_id' => env('RAJAONGKIR_ORIGIN_CITY', '109'),

    /*
    |--------------------------------------------------------------------------
    | Courier list (digunakan untuk kalkulasi ongkir checkout)
    |--------------------------------------------------------------------------
    */
    'couriers' => ['jne', 'jnt', 'pos'],
];
