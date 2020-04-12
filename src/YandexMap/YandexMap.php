<?php

namespace src\YandexMap;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use StdClass;
use Yii;
use yii\web\Response;

/**
 * Класс для работы с яндекс картой
 *
 * Class YandexMap
 *
 * @package src\YandexMap
 */
class YandexMap
{
    /** @var string ключ пользователя */
    public $apiKey;

    /** @var string адрес запроса */
    private const URL = 'https://geocode-maps.yandex.ru/1.x/';

    /** @var string язык ответа */
    private $lang = 'ru_RU';

    /** @var string формат ответа */
    private $format = Response::FORMAT_JSON;

    /** @var integer время существования записи в кэше */
    private $cacheLifeTime = 86400;

    /**
     * Запрос к серверу яндекс карт с целью получить свписок необходимых координат и локаций
     *
     * @param string $geocode - строка с названием или координатами нужной локации
     *
     * @return GuzzleResponse - список найденных локаций
     **/
    public function getDataMap(string $geocode): string
    {
        $cache = Yii::$app->cache;
        $content = '';
        $key = md5($geocode);

        if ($cache && ($content = $cache->get($key))) {
            return $content;
        }

        $content = (new Client(['base_uri' => self::URL]))->request('GET', '', [
            'query' => [
                'geocode' => $geocode,
                'apikey' => $this->apiKey,
                'format' => $this->format,
                'lang' => $this->lang,
            ],
        ])->getBody()->getContents();

        if ($cache) {
            $cache->set($key, $content, $this->cacheLifeTime);
        }

        return $content;
    }

    /**
     * Получение координат для локации
     *
     * @param string $geocode - строка с названием или координатами нужной локации
     *
     * @return string|null
     */
    public function getPosition(string $geocode): ?string
    {
        $content = json_decode($this->getDataMap($geocode));
        if (!$content
            || (int)$content->response->GeoObjectCollection->metaDataProperty->GeocoderResponseMetaData->found
            === 0
        ) {
            return null;
        }

        return $content->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;
    }

    /**
     * Получение локации по координатам
     *
     * @param float $lat  - дробное число со значением широты
     * @param float $long - дробное число со значением долготы
     *
     * @return StdClass|null
     */
    public function getAddressByPositions(float $lat, float $long): ?StdClass
    {
        $content = json_decode($this->getDataMap("$long $lat"));
        if (!$content
            || (int)$content->response->GeoObjectCollection->metaDataProperty->GeocoderResponseMetaData->found
            === 0
        ) {
            return null;
        }

        return $content->response->GeoObjectCollection->featureMember[0]->GeoObject->metaDataProperty
            ->GeocoderMetaData->AddressDetails->Country;
    }
}
