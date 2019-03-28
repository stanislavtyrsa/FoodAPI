<?php
/**
 * Created by PhpStorm.
 * User: stanislav
 * Date: 2019-03-28
 * Time: 19:38
 */

interface IController
{
    /**
     * @param string $execMethod
     * @param array $params
     * @param array $uri
     * @param string $action
     * @param string $method
     * @return mixed
     */
    public function execute(string $execMethod, array &$params, array &$uri, string $action, string $method);
}