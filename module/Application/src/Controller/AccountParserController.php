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
            $username = $this->params()->fromRoute('username', null);
            if (!isset($username)) {
                /*
                * Эта ветка не должна запускаться, т.к. дано значение по умолчанию
                * внутри modules.config.php
                */
                $result = array(
                    'error' => true,
                    'message' => 'Имя пользователя Instagram не задано.',
                    'post_urls' => array()
                );
            }
            else {

                $document = $this->dom()->createFrom($this->page->getDom(self::URI . $username));
                $encoding = $document->getDomDocument()->encoding;
                $scripts = Query::execute('body > script', $document, Query::TYPE_CSS);



                foreach ($scripts as $script) {
                    $scriptText = trim($script->textContent); // содержит тело скрипта
                    if (($position = mb_strpos($scriptText, "window._sharedData = ")) !== FALSE) {
                        $dataJSON = mb_substr($scriptText,
                            $position + mb_strlen("window._sharedData = "), -1);
                        $data = json_decode($dataJSON, true);
                        break;
                    }
                }

                $profilePage = $data['entry_data']['ProfilePage'][0]['graphql']['user'] ?? array();

                $result = array(
                    'error' => false,
                    'post_urls' => array(),
                    'raw_data' => $profilePage,
                );
            }
            return new JsonModel(array('response' => $result));
        }
    }