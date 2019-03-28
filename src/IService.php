<?php
/**
 * Created by PhpStorm.
 * User: stanislav
 * Date: 2019-03-28
 * Time: 17:34
 */

interface IService
{
    /**
     * @throws Exception
     */
    public function run();

    /**
     * @param $data
     * @param $errorCode
     * @return string|null
     */
    public function sendResponse($data, $errorCode = 500): ?string;
}