<?php

/**
 * Created by PhpStorm.
 * User: stanislav
 * Date: 2019-03-28
 * Time: 17:31
 */


/**
 * Class Service
 */
class Service implements IService
{

    const HTTP_OK = 200;
    const HTTP_NOT_FOUND = 404;
    const HTTP_METHOD_NOT_ALLOWED = 405;
    const HTTP_INTERNAL_SERVER_ERROR = 500;

    const HTTP_POST = 'POST';
    const HTTP_GET = 'GET';
    const HTTP_PUT = 'PUT';
    const HTTP_DELETE = 'DELETE';

    const EXEC_CONTROLLER = 'controllers';
    const EXEC_METHODS = 'methods';
    const EXEC_METHOD = 'method';


    protected $method = '';
    protected $requestUri = [];
    protected $requestParams = [];
    protected $action = '';

    /**
     * Service constructor.
     * @throws Exception
     */
    public function __construct()
    {
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");
        $this->requestUri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $this->requestParams = $_REQUEST;
        $this->method = $_SERVER['REQUEST_METHOD'];

        if ($this->method == self::HTTP_POST && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == self::HTTP_DELETE) {
                $this->method = self::HTTP_DELETE;
            } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == self::HTTP_PUT) {
                $this->method = self::HTTP_PUT;
            } else {
                throw new Exception("Unexpected Header");
            }
        }
    }


    /**
     * @param int $code
     * @return string
     */
    public function getStatus(int $code): string
    {
        $status = [
            200 => 'OK',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error'
        ];
        return $status[$code] !== null ? $status[$code] : $status[500];
    }

    protected function getAction()
    {
        return [
            'order' => [
                self::EXEC_CONTROLLER => BaseController::class,
                'createOrder' => [
                    self::EXEC_METHOD => 'createOrder',
                    self::EXEC_METHODS => self::HTTP_POST
                ],
                'editStatus' => [
                    self::EXEC_METHOD => 'editStatus',
                    self::EXEC_METHODS => self::HTTP_POST
                ],
                'getOrders' => [
                    self::EXEC_METHOD => 'getOrders',
                    self::EXEC_METHODS => self::HTTP_GET
                ],
                'getDetails' => [
                    self::EXEC_METHOD => 'getDetails',
                    self::EXEC_METHODS => self::HTTP_GET
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function sendResponse($data, $errorCode = 500): ?string
    {
        header("HTTP/1.1 " . $errorCode . " " . $this->getStatus($errorCode));
        return json_encode($data);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->action = $this->getAction();
        if (array_shift($this->requestUri) !== 'api') {
            throw new RuntimeException('API Not Found', self::HTTP_NOT_FOUND);
        }
        $controllerName = array_shift($this->requestUri);
        $controllerInfo = $this->action[$controllerName] ?? null;
        if ($controllerInfo === null) {
            throw new RuntimeException('Controller Not Found', self::HTTP_NOT_FOUND);
        }

        $methodURI = array_shift($this->requestUri);
        $methodRoute = $controllerInfo[$methodURI] ?? null;
        if ($methodRoute === null) {
            throw new RuntimeException('Method Not Found', self::HTTP_NOT_FOUND);
        }
        $executableMethod = $methodRoute[self::EXEC_METHOD];
        $httpMethod = $methodRoute[self::EXEC_METHODS];
        if ($this->method !== $httpMethod) {
            throw new RuntimeException("{$methodURI} does not support method {$this->method}", self::HTTP_NOT_FOUND);
        }

        $controller = $controllerInfo[self::EXEC_CONTROLLER];
        /** @var IController $instance */
        $instance = new $controller();
        return $instance->execute($executableMethod, $this->requestParams, $this->requestUri, $this->action, $this->method);
    }
}