<?php namespace ZChat\Core;

/**
 * Class App
 * @package ZChat\Core
 */
class App
{
    /**
     * @var string
     */
    protected $controllerPath = "ZChat\Controller\Home";

    /**
     * @var object
     */
    protected $controller;

    /**
     * @var string
     */
    protected $method = "index";

    /**
     * @var array
     */
    protected $params = [];

    /**
     * App constructor.
     */
    public function __construct()
    {
        $url = $this->parseUrl();

        if (class_exists("ZChat\\Controller\\" . ucfirst($url[0]))) {
            $this->controllerPath = "ZChat\\Controller\\" . ucfirst($url[0]);

            unset($url[0]);
        }

        $this->controller = new $this->controllerPath();

        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];

                unset($url[1]);
            }
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /**
     * @return array
     */
    public function parseUrl()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
    }
}
