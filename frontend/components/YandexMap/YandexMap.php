<?php
namespace frontend\components\YandexMap;

use frontend\components\DebugHelper\DebugHelper;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
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
}
