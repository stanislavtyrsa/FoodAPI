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
    const DB_NAME = 'orders';

    public function createOrder()
    {
        try {
            $records = $this->getRequestParams();
            PDOProvider::getInstance()->createQuery(self::DB_NAME, $records);
            $this->sendResponse(self::SUCCESS_STATUS, self::HTTP_OK);
        } catch (Exception $e) {
            $this->sendResponse($e->getMessage(), self::HTTP_NOT_FOUND);
        }
    }

    public function editStatus()
    {
        try {
            $records = $this->getRequestParams();
            PDOProvider::getInstance()->updateQuery(self::DB_NAME, $records);
            $this->sendResponse(self::SUCCESS_STATUS, self::HTTP_OK);
        } catch (Exception $e) {
            $this->sendResponse($e->getMessage(), self::HTTP_NOT_FOUND);
        }
    }

    public function getOrders()
    {
        try {
            $result = PDOProvider::getInstance()->selectQuery(self::DB_NAME);
            $this->sendResponse($result, self::HTTP_OK);
        } catch (Exception $e) {
            $this->sendResponse($e->getMessage(), self::HTTP_NOT_FOUND);
        }
    }

    public function getDetails()
    {
        try {
            $id = $this->get(self::ID);
            $fields = ['id, title, amount, address, time'];
            $result = PDOProvider::getInstance()->selectQuery(
                self::DB_NAME,
                $fields,
                self::ID, $id
            );
            $this->sendResponse($result, self::HTTP_OK);
        } catch (Exception $e) {
            $this->sendResponse($e->getMessage(), self::HTTP_NOT_FOUND);
        }
    }
}