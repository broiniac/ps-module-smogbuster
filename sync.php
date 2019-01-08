<?php

ini_set('max_execution_time', 3600);

require_once dirname(__FILE__).'/../../config/config.inc.php';
include_once dirname(__FILE__).'/smogbuster.php';

class SmogBusterFetcher
{
    const STATIONS_URL = 'http://api.gios.gov.pl/pjp-api/rest/station/findAll';

    const AQ_INDEX_URL = 'http://api.gios.gov.pl/pjp-api/rest/aqindex/getIndex/';

    /**
     * Fetch data from api and persist into database.
     *
     * @return int count of persisted rows
     */
    public function fetch()
    {
        $i = 0;
        $stations = $this->curl(self::STATIONS_URL);
        if (is_array($stations)) {
            foreach ($stations as $station) {
                $data = [];
                $emptyIndexDef = -1;
                $data['station_id'] = isset($station['id']) ? $station['id'] : null;
                if (empty($data['station_id'])) {
                    continue;
                }

                $data['name'] = isset($station['stationName']) ? $station['stationName'] : null;
                $data['latitude'] = isset($station['gegrLat']) ? $station['gegrLat'] : null;
                $data['longitude'] = isset($station['gegrLon']) ? $station['gegrLon'] : null;
                $data['city'] = isset($station['city']['name']) ? $station['city']['name'] : null;
                $data['address'] = isset($station['addressStreet']) ? $station['addressStreet'] : null;

                $aqIndex = $this->curl(self::AQ_INDEX_URL.$station['id']);
                $data['st'] = isset($aqIndex['stIndexLevel']['id']) ? $aqIndex['stIndexLevel']['id'] : $emptyIndexDef;
                $data['so2'] = isset($aqIndex['so2IndexLevel']['id']) ? $aqIndex['so2IndexLevel']['id'] : $emptyIndexDef;
                $data['no2'] = isset($aqIndex['no2IndexLevel']['id']) ? $aqIndex['no2IndexLevel']['id'] : $emptyIndexDef;
                $data['co'] = isset($aqIndex['coIndexLevel']['id']) ? $aqIndex['coIndexLevel']['id'] : $emptyIndexDef;
                $data['pm10'] = isset($aqIndex['pm10IndexLevel']['id']) ? $aqIndex['pm10IndexLevel']['id'] : $emptyIndexDef;
                $data['pm25'] = isset($aqIndex['pm25IndexLevel']['id']) ? $aqIndex['pm25IndexLevel']['id'] : $emptyIndexDef;
                $data['o3'] = isset($aqIndex['o3IndexLevel']['id']) ? $aqIndex['o3IndexLevel']['id'] : $emptyIndexDef;
                $data['c6h6'] = isset($aqIndex['c6h6IndexLevel']['id']) ? $aqIndex['c6h6IndexLevel']['id'] : $emptyIndexDef;
                $data['updated_at'] = (new \DateTime())->format('Y-m-d H:i:s');

                $this->updateSmogBuster($data);
                ++$i;
            }
        }

        return $i;
    }

    /**
     * Get database instance.
     *
     * @return Db
     */
    public function getDb()
    {
        return Db::getInstance();
    }

    /**
     * Simple curl helper.
     *
     * @param string $url
     *
     * @return array
     */
    public function curl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if (200 != $httpCode) {
            throw new \Exception($result);
        }

        return json_decode($result, true);
    }

    /**
     * Update database table.
     *
     * @param array $data
     */
    public function updateSmogBuster($data)
    {
        $this->getDb()->update('smogbuster', $data, 'station_id = '.$data['station_id']);
        if (!$this->getDb()->numRows()) {
            unset($data['updated_at']);
            $data['created_at'] = (new \DateTime())->format('Y-m-d H:i:s');
            $this->getDb()->insert('smogbuster', $data);
        }
    }
}

$smogBusterFetcher = new SmogBusterFetcher();
$result = $smogBusterFetcher->fetch();

echo '<p>Fetched results: '.$result.'</p>';

die;
