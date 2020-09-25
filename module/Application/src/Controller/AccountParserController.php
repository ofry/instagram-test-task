<?php
    /**
     * Created by PhpStorm.
     * User: ofryak
     * Date: 06.08.18
     * Time: 21:13
     */

    namespace Application\Controller;

    use Laminas\Dom\Document;
    use Laminas\Mvc\Controller\AbstractRestfulController;
    use Application\Model\WebPage;
    use Laminas\Dom\Document\Query;
    use Laminas\Hydrator\ClassMethodsHydrator;
    use Laminas\View\Model\JsonModel;

    /**
     * Class AccountParserController
     *
     * @package Application\Controller
     */
    class AccountParserController extends AbstractRestfulController
    {
        /**
         * URL сайта
         */
        const URI = 'https://www.instagram.com/';

        /**
         * @var \Application\Model\WebPage page
         */
        private $page;

        /**
         * @var \Laminas\Hydrator\ClassMethodsHydrator hydrator
         */
        private $hydrator;

        /**
         * AccountParserController constructor.
         *
         * @param \Application\Model\WebPage     $page
         */
        public function __construct(WebPage $page)
        {
            $this->page = $page;
            $this->hydrator = new ClassMethodsHydrator();
        }

        /**
         * @return \Laminas\View\Model\JsonModel
         */
        public function indexAction()
        {
            $result = array();
            return new JsonModel(array('response' => $result));
        }
    }