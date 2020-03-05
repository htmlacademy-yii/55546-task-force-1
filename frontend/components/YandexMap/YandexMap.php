<?php
namespace frontend\components\YandexMap;

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

    /** @var integer время существования записи в кэше */
    public $cacheLifeTime = 86400;

    /**
     * @param string $geocode
     * @return GuzzleResponse
     **/
    public function getDataMap(string $geocode): string
    {
        $cache = Yii::$app->cache;
        $content = '';
        $key = md5($geocode);

        if($cache && ($content = $cache->get($key))) {
            return $content;
        }

        $content = (new Client(['base_uri' => self::URL]))->request('GET', '', [
            'query' => [
                'geocode' => $geocode,
                'apikey' => $this->apiKey,
                'format' => $this->format,
                'lang' => $this->lang,
            ]
        ])->getBody()->getContents();

        if ($cache) {
            $cache->set($key, $content, $this->cacheLifeTime);
        }

        return $content;
    }

    /**
     * @param string $geocode
     * @return string
     **/
    public function getPosition(string $geocode): string
    {
        $content = json_decode($this->getDataMap($geocode))
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
        $content = json_decode($this->getDataMap("$long $lat"))
            ->response->GeoObjectCollection;
        if((int) $content->metaDataProperty->GeocoderResponseMetaData->found === 0) {
            return false;
        }

        return $content->featureMember[0]->GeoObject->metaDataProperty
            ->GeocoderMetaData->AddressDetails->Country;
    }
}
