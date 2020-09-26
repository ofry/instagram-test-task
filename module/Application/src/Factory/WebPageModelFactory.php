<?php
    /**
     * Created by PhpStorm.
     * User: ofryak
     * Date: 05.08.18
     * Time: 9:38
     */

    namespace Application\Factory;

    use Application\Model\WebPage;
    use Laminas\ServiceManager\Factory\FactoryInterface;
    use Interop\Container\ContainerInterface;

    /**
     * Class WebPageModelFactory Фабрика, описывающая веб-ресурс
     *
     * @package Application\Factory
     */
    class WebPageModelFactory implements FactoryInterface
    {
        /**
         * @param \Interop\Container\ContainerInterface $container
         * @param string                                $requestedName
         * @param array|null                            $options
         *
         * @return \Application\Model\WebPage
         */
        public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
        {
            $logger = $container->get('MyLogger');
            $config = $container->get('config');
            $client_options = $config['client']['options'] ?? array();
            return new WebPage($logger, $client_options);
        }
    }