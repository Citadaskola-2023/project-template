<?php

require_once  dirname(__DIR__) . '/vendor/autoload.php';

// Uzdevums: Iegūt vidējo gaisa temperatūru Liepājā 1. jūnijā
// Expectd result: 10.6375

// Rsources:
// Meteoroloģiskās stacijas: 79e707d8-6719-49b2-92b1-ec261451f1d9
// Meteoroloģiskie arhīva dati: ecc62e27-2071-483c-bca9-5e53d979faa8

// Todo:
// 1. veikt izsaukumu uz staciju endpointu, dabūt Liepājas stacijas ID
// 2. veikt izsaukumu uz arhīvu ar parametriem: stacijas id, TDRY, 2023-06-01

// URL: https://data.gov.lv/dati/lv/api/3/action/datastore_search

// izmantojam: file_get_contents();

enum GovData: string {
    public const BASEURL = 'https://data.gov.lv/dati/lv/api/3/action/datastore_search';

    case Stations = '79e707d8-6719-49b2-92b1-ec261451f1d9';
    case HistoricalData = 'ecc62e27-2071-483c-bca9-5e53d979faa8';

    public function getResourceId(): string
    {
        return $this->value;
    }
}


$city = $_GET['city'] ?? '';  // https://localhost/?city=Li

if (!$city) {  // https://localhost/?city=
    throw new \InvalidArgumentException('Izvēlies pilsētu');
}

function getResults(GovData $resource, array $params = []): array
{
    $params = http_build_query(array_merge([
        'resource_id' => $resource->getResourceId(),
    ], $params));

    $url = GovData::BASEURL . '?' . $params;

    $contents = file_get_contents($url);
    $json = json_decode($contents);

    return $json->result->records;
}

$records = getResults(GovData::Stations);

$filtered = array_filter($records, function ($record) use ($city) {
    return str_contains(strtolower($record->NAME), strtolower($city));
});

if (!count($filtered)) { // https://localhost/?city=y - 0 ierakstu
    throw new \InvalidArgumentException('Neatradām staciju');
}

if (count($filtered) > 1) { // https://localhost/?city=Li - 3 ieraksti
    throw new \InvalidArgumentException('Pārāk daudz rezultāti, esi precīzāks');
}

$station = current($filtered);

$stationId = $station->STATION_ID;

// --- END TODO 1 ---

$date = $_GET['date'] ?? date('Y-m-d', strtotime('yesterday')); // https://localhost/?city=Li&date=2023-01-20

$records = getResults(GovData::HistoricalData, [
    'q' => implode(',', ['TDRY', $date, $stationId]),
]);

if (!count($records)) {
    throw new InvalidArgumentException(
        'Nav datu, pēc kā aparēķināt vidējo temperatūru stacijā ' . $station->NAME
    );
}

$total = 0;
foreach ($records as $record) {
    $total += $record->VALUE;
}

$average = $total / count($records);

dump($average);

dump('https://ej.uz/salida');


