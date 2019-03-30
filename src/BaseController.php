<?php
/**
 * Created by PhpStorm.
 * User: stanislav
 * Date: 2019-03-28
 * Time: 18:34
 */

abstract class BaseController extends Service implements IController
{
    const ID = 'id';

    public function execute(string $execMethod, array &$params, array &$uri, string $method)
    {
        $this->requestParams = $params;
        $this->method = $method;
        $this->requestUri = $uri;
        $this->{$execMethod}();
    }

    /**
     * @param $param
     * @return mixed|null
     */
    protected function get($param)
    {
        return $this->requestParams[$param] ?? null;
    }

    /**
     * @return array
     */
    public function getRequestParams(): array
    {
        return $this->requestParams;
    }

}