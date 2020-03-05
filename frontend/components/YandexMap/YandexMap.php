<?php
namespace frontend\components\YandexMap;

use frontend\components\DebugHelper\DebugHelper;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Yii;
use yii\web\Response;

class YandexMap
{
    /** @var string адрес запроса */
    const URL = 'https://geocode-maps.yandex.ru/1.x/';

    /** @var string ключ пользователя */
    public $apiKey;

    /** @var string язык ответа */
    public $lang = 'ru_RU';

    /** @var string формат ответа */
    public $format = Response::FORMAT_JSON;

    /**
     * @param string $geocode
     * @return GuzzleResponse
     **/
    private function getDataMap(string $geocode): GuzzleResponse
    {
        return (new Client(['base_uri' => self::URL]))->request('GET', '', [
            'query' => [
                'geocode' => $geocode,
                'apikey' => $this->apiKey,
                'format' => $this->format,
                'lang' => $this->lang,
            ]
        ]);
    }

    /**
     * @param string $geocode
     * @return string
     **/
    public function getPosition(string $geocode): string
    {
        $content = json_decode($this->getDataMap($geocode)->getBody()->getContents())
            ->response->GeoObjectCollection;
        if((int) $content->metaDataProperty->GeocoderResponseMetaData->found === 0) {
            return false;
        }

        return $content->featureMember[0]->GeoObject->Point->pos;
    }

    /**
     * @param float $lat
     * @param float $long
     * @return \stdClass
     **/
    public function getAddressByPositions(float $lat, float $long): \stdClass
    {
        $content = json_decode($this->getDataMap("$long $lat")->getBody()->getContents())
            ->response->GeoObjectCollection;
        if((int) $content->metaDataProperty->GeocoderResponseMetaData->found === 0) {
            return false;
        }

        return $content->featureMember[0]->GeoObject->metaDataProperty->GeocoderMetaData->AddressDetails->Country;
    }

    public function getResultList($geocode)
    {
        return json_decode($this->getDataMap($geocode)->getBody()->getContents())
            ->response->GeoObjectCollection->featureMember;
    }

    public function getPlaceFromCache(string $place = '')
    {
        if(empty($place)) {
            return [];
        }

        $cache = Yii::$app->cache;
        // если кэш redis не доступен, то просто возвращаем список с результатами напрямую от яндекса
        if(!$cache) {
            return $this->getResultList($place);
        }

        $place_key = md5($place);
        // проверяем кэш redis по ключу, если не находим, делаем запрос к яндексу, результат записываем в кэш
        if(!$cache->get($place_key)) {
            $cache->set($place_key, json_encode($this->getResultList($place)), 86400);
        }

        // возвращаем кэш
        return json_decode($cache->get($place_key));
    }

    public function getLocationFromCache(int $lat, int $long)
    {
        if(!$lat || !$long) {
            return null;
        }

        $cache = Yii::$app->cache;
        if(!$cache) {
            return $this->getAddressByPositions($lat, $long);
        }

        $locationKey = md5("location-$lat-$long");
        if(!Yii::$app->cache->get($locationKey)) {
            Yii::$app->cache->set($locationKey, json_encode($this->getAddressByPositions($lat, $long)),86400);
        }

        return json_decode(Yii::$app->cache->get($locationKey));
    }
}
