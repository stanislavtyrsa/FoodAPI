<?php

require_once 'system.php';

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class MainTest extends TestCase
{
    /** @var PDOProvider $pdo */
    private $pdo = null;

    private $dataSet = [];

    public function setUp()
    {
        $this->pdo = PDOProvider::getInstance();
        $this->dataSet = [
            "title" => "Item 1",
            "amount" => 10,
            "address" => "Nevsky Avenue, 90",
            "time" => "2019-02-18 14:18:53"
        ];
    }
    public function testUpdateQuery()
    {
        try {
            $data = [
                'id' => 2,
                'title' => 'newVal',
            ];
            $this->pdo->updateQuery(OrderController::DB_NAME, $data);
            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail($e->getMessage());
        }
    }
    public function testSelectQuery()
    {
        try {
            $data = [
                'id',
                'title',
            ];
            $this->pdo->selectQuery(OrderController::DB_NAME, $data);
            self::assertTrue(true);
            $this->pdo->selectQuery(OrderController::DB_NAME, $data, 'id', 2);
            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail($e->getMessage());
        }
    }

    public function testInsertQuery()
    {
        try {
            $data = [
                'title' => 'newVal',
                'amount' => 10
            ];
            $this->pdo->createQuery(OrderController::DB_NAME, $data);
            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail($e->getMessage());
        }
    }

    public function testCreateOrder()
    {
        $client = new Client('http://127.0.0.1:80', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));
        $request = $client->post('/api/order/createOrder', null, json_encode($this->dataSet));
        $response = $request->send();
        $this->assertEquals(BaseController::HTTP_OK, $response->getStatusCode());
    }

    public function testGetDetails()
    {
        $client = new Client('http://127.0.0.1:80', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));
        $response = $client->request('GET', 'http://127.0.0.1:80:/api/order/getDetails?id=1');
        $this->assertEquals(BaseController::HTTP_OK, $response->getStatusCode());
        $response = $client->request('GET', 'http://127.0.0.1:80:/api/order/getDetails');
        $this->assertEquals(BaseController::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}