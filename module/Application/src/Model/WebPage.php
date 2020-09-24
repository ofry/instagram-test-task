<?php


namespace Application\Model;

use Laminas\Http\Client;
use Laminas\Log\Logger;

/**
 * Class WebPage
 *
 * @package Application\Model
 */
class WebPage
{
    /**
     * @var array
     */
    private $client_options;
    /**
     * @var \Laminas\Log\Logger
     */
    private $logger;

    /**
     * WebPage constructor.
     *
     * @param \Laminas\Log\Logger $logger
     * @param array            $options
     */
    public function __construct(Logger $logger, $options = array())
    {
        $this->client_options = $options;
        $this->logger = $logger;
    }

    /**
     * Возвращает body запроса ресурса $uri
     *
     * @param string|null $uri адрес ресурса
     *
     * @return string
     */
    public function getDom($uri = null)
    {
        /**
         * @var \Laminas\Http\Client
         */
        $client = new Client($uri, $this->client_options);
        /**
         * @var \Laminas\Http\Response полученный ответ
         */
        $response = $client->send();
        if ($response->isSuccess()) {
            $result = $response->getBody();
        } else {
            $result = '';
        }
        return $result;
    }
}