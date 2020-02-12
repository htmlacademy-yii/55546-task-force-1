<?php
namespace frontend\components\YandexMap;

use frontend\components\DebugHelper\DebugHelper;
use GuzzleHttp\Client;
use yii\web\Response;

class YandexMap
{
    const URL = 'https://geocode-maps.yandex.ru/1.x/';
    const API_KEY = 'e666f398-c983-4bde-8f14-e3fec900592a';
    const FORMAT = Response::FORMAT_JSON;
    const LANG = 'ru_RU';

    private static function getDataMap($geocode)
    {
        return (new Client(['base_uri' => self::URL]))->request('GET', '', [
            'query' => [
                'geocode' => $geocode,
                'apikey' => self::API_KEY,
                'format' => self::FORMAT,
                'lang' => self::LANG,
            ]
        ]);
    }

    public static function getPosition($geocode)
    {
        $content = json_decode(self::getDataMap($geocode)->getBody()->getContents())
            ->response->GeoObjectCollection;
        if((int) $content->metaDataProperty->GeocoderResponseMetaData->found === 0) {
            return false;
        }

        return $content->featureMember[0]->GeoObject->Point->pos;
    }

    public static function getAddressByPositions($lat, $long)
    {
        $content = json_decode(self::getDataMap("$long $lat")->getBody()->getContents())
            ->response->GeoObjectCollection;
        if((int) $content->metaDataProperty->GeocoderResponseMetaData->found === 0) {
            return false;
        }

        return $content->featureMember[0]->GeoObject->metaDataProperty->GeocoderMetaData->AddressDetails->Country;
    }
}
