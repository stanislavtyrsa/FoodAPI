<?php
/**
 * Created by PhpStorm.
 * User: stanislav
 * Date: 2019-03-28
 * Time: 19:50
 */

class OrderController extends BaseController
{
    const SUCCESS_STATUS = 'Success';

    public function createOrder()
    {
        try {
            $this->sendResponse(self::SUCCESS_STATUS, self::HTTP_OK);
        } catch (Exception $e) {
            $this->sendResponse($e->getMessage(), self::HTTP_NOT_FOUND);
        }
    }

    public function editStatus()
    {
        try {
            $this->sendResponse(self::SUCCESS_STATUS, self::HTTP_OK);
        } catch (Exception $e) {
            $this->sendResponse($e->getMessage(), self::HTTP_NOT_FOUND);
        }
    }

    public function getOrders()
    {
        try {
            $this->sendResponse(self::SUCCESS_STATUS, self::HTTP_OK);
        } catch (Exception $e) {
            $this->sendResponse($e->getMessage(), self::HTTP_NOT_FOUND);
        }
    }

    public function getDetails()
    {
        try {
            $this->sendResponse(self::SUCCESS_STATUS, self::HTTP_OK);
        } catch (Exception $e) {
            $this->sendResponse($e->getMessage(), self::HTTP_NOT_FOUND);
        }
    }
}