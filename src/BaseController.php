<?php
/**
 * Created by PhpStorm.
 * User: stanislav
 * Date: 2019-03-28
 * Time: 18:34
 */

abstract class BaseController extends Service implements IController
{
    public function execute(string $execMethod, array &$params, array &$uri, string $action, string $method)
    {
        $this->requestParams = $params;
        $this->method = $method;
        $this->action = $action;
        $this->requestUri = $uri;
        $this->{$execMethod}();
    }


    /**
     * @param $param
     * @return mixed|null
     */
    private function get($param)
    {
        return $this->params[$param] ?? null;
    }
}