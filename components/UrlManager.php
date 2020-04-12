<?php

namespace app\components;

use yii\web\Request;
use yii\base\InvalidConfigException;

class UrlManager extends \yii\web\UrlManager
{

    public $reactRoute = '/';

    public $yiiRoutes = [
        "api",
        "debug",
        "gii",
    ];

    /**
     * Parses the user request.
     * @param Request $request the request component
     * @return array|bool the route and the associated parameters. The latter is always empty
     * if [[enablePrettyUrl]] is `false`. `false` is returned if the current request cannot be successfully parsed.
     * @throws InvalidConfigException
     */
    public function parseRequest($request)
    {
        // check if we're calling a route that should be processed by yii

        $pathInfo = $request->getPathInfo();

        foreach ($this->yiiRoutes as $yiiRoute) {

            // check for direct route or a prefixed route, eg, "gii" or "gii/*"

            $yiiRoutePrefix = "$yiiRoute/";

            if ($pathInfo === $yiiRoute || strpos($pathInfo, $yiiRoutePrefix) === 0) {

                return parent::parseRequest($request);

            }

        }

        // use frontend route

        $request->setPathInfo($this->reactRoute);

        return parent::parseRequest($request);
    }
}