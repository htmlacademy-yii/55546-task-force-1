<?php
namespace frontend\modules\api\v1\src;

use yii\web\UrlManager;
use yii\web\UrlRuleInterface;
use yii\web\Request;
use yii\base\BaseObject;

/**
 * Class RestMessagesUrlRule
 *
 * @package frontend\modules\api\src
 */
class RestMessagesUrlRule extends BaseObject implements UrlRuleInterface
{
    /** @var string */
    public $pattern = '';
    /** @var array */
    public $routes = [];
    /** @var array */
    public $verbs = [];
    /**
     * {@inheritDoc}
     * @see \yii\base\BaseObject::init()
     * */
    public function init(): void
    {
        if (empty($this->verbs)) {
            $this->verbs = array_keys($this->routes);
        }
    }

    /**
     * Creates a URL according to the given route and parameters.
     * @param UrlManager $manager the URL manager
     * @param string $route the route. It should not have slashes at the beginning or the end.
     * @param array $params the parameters
     * @return string|bool the created URL, or false if this rule cannot be used for creating this URL.
     */
    public function createUrl($manager, $route, $params): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     * @see \yii\web\UrlRuleInterface::parseRequest()
     * */
    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();
        $matches = [];
        if(in_array($request->getMethod(), $this->verbs) && preg_match($this->pattern, $pathInfo, $matches)) {
            switch ($request->getMethod()) {
                case 'GET':
                    return $this->parseGet($matches, $request);
                break;
                case 'POST':
                    return $this->parsePost($matches, $request);
                break;
                default:
                    return false;
                break;
            }
        }
        return false;
    }

    /**
     * @param array $matches
     * @param Request $request
     * @return array
     * */
    private function parseGet(array $matches, Request $request): array
    {
        $filter = ['filter' => [],];
        foreach ($matches as $param => $value) {
            if(is_string($param)) {
                $filter['filter'][$param] = $value;
            }
        }
        return [$this->routes['GET'] ?? $request->getPathInfo(), $filter];
    }

    /**
     * @param array $matches
     * @param Request $request
     * */
    private function parsePost(array $matches, Request $request): array
    {
        $bodyParams = $request->getBodyParams();
        foreach ($matches as $param => $value) {
            if(is_string($param)) {
                $bodyParams[$param] = $value;
            }
        }
        $rawBody = json_decode($request->getRawBody(), true);
        if((json_last_error() == JSON_ERROR_NONE) && is_array($rawBody)) {
            $bodyParams = array_merge($bodyParams, $rawBody);
        }
        $request->setBodyParams($bodyParams);
        return [$this->routes['POST'] ?? $request->getPathInfo(), []];
    }
}
