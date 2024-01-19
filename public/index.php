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

$city = $_GET['city'] ?? '';  // https://localhost/?city=Li
$date = $_GET['date'] ?? date('Y-m-d', strtotime('yesterday')); // https://localhost/?city=Li&date=2023-01-20

enum GovData: string
{
    case Stations = '79e707d8-6719-49b2-92b1-ec261451f1d9';
    case HistoricalData = 'ecc62e27-2071-483c-bca9-5e53d979faa8';

    public function getResourceId(): string
    {
        return $this->value;
    }
}

class DataGovLv
{
    private const BASEURL = 'https://data.gov.lv/dati/lv/api/3/action/datastore_search';

    public function getResults(GovData $resource, array $params = []): array
    {
        $params = http_build_query(array_merge([
            'resource_id' => $resource->getResourceId(),
        ], $params));

        $url = self::BASEURL . '?' . $params;

        $contents = file_get_contents($url);
        $json = json_decode($contents);

        return $json->result->records;
    }
}

readonly class Stations
{
    public function __construct(
        private DataGovLv $api
    )
    {
    }

    public function getMeteoStationByCityName(string $name = '')
    {
        if (!$name) {
            throw new \InvalidArgumentException('Izvēlies pilsētu');
        }

        $filtered = array_filter(
            $this->api->getResults(GovData::Stations),
            fn($station) => str_contains(strtolower($station->NAME), strtolower($name))
        );

        if (!count($filtered)) { // https://localhost/?city=y - 0 ierakstu
            throw new \InvalidArgumentException('Neatradām staciju');
        }

        if (count($filtered) > 1) { // https://localhost/?city=Li - 3 ieraksti
            throw new \InvalidArgumentException('Pārāk daudz rezultāti, esi precīzāks');
        }

        $one = current($filtered);

        return $one->STATION_ID;
    }
}

class HistoricalMeteoData
{
    private array $records = [];

    public function __construct(
        private readonly DataGovLv $api,
        private readonly HistoricalDataFilter $filter
    )
    {
        $this->fetchData();
    }

    private function fetchData()
    {
        $this->records = $this->api->getResults(GovData::HistoricalData, [
            'q' => (string) $this->filter,
        ]);

        if (!count($this->records)) {
            throw new InvalidArgumentException('Nav vēsturisko datu');
        }
    }

    public function getAverage(): float
    {
        $total = 0;
        foreach ($this->records as $record) {
            $total += $record->VALUE;
        }

        return $total / count($this->records);
    }

    public function getAtTime(int $hour = 8): float
    {
        $str = sprintf('T%02d:00:00', $hour);
        $filtered = array_filter($this->records, fn ($record) => str_ends_with($record->DATETIME, $str));

        return current($filtered)->VALUE;
    }

    public function getMin(): float
    {
        $values = array_map(fn ($record) => $record->VALUE, $this->records);
        return min($values);
    }

    public function getMax(): float
    {
        $values = array_map(fn ($record) => $record->VALUE, $this->records);
        return max($values);
    }
}

class HistoricalDataFilter
{
    public function __construct(
        public string $stationId,
        public string $type,
        public string $date,
    )
    {
    }

    public function __toString(): string
    {
        return implode(',', [$this->type, $this->stationId, $this->date]);
    }
}


try {
    $api = new DataGovLv();
    $station = (new Stations($api))->getMeteoStationByCityName($city);
    $meteoData = new HistoricalMeteoData($api, new HistoricalDataFilter($station, 'TDRY', $date));

    dump('avg: ' . round($meteoData->getAverage(), 2));
    dump('at 2PM: ' . $meteoData->getAtTime(14));
    dump('min: '. $meteoData->getMin());
    dump('max: ' . $meteoData->getMax());
} catch (Exception $e) {
    dump('Diemžēl nevarēja ielasīt datus. Kļūda: ' . $e->getMessage());
}



