<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-skeleton for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-skeleton/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Application;

use Application\Controller\Plugin\DomExtender;
use Application\Factory\WebPageFactory;
use Application\Factory\WebPageModelFactory;
use Application\Model\WebPage;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'log' => [
        'MyLogger' => [
            'writers'    => [
                'syslog' => [
                    'name'     => 'syslog',
                    'priority' => \Laminas\Log\Logger::ALERT,
                    'options'  => [
                        'formatter' => [
                            'name'    => \Laminas\Log\Formatter\Simple::class,
                            'options' => [
                                'format'         => '%timestamp% %priorityName% (%priority%): %message% %extra%',
                                'dateTimeFormat' => 'r',
                            ],
                        ],
                        'filters'   => [
                            'priority' => [
                                'name'    => 'priority',
                                'options' => [
                                    'operator' => '<=',
                                    'priority' => \Laminas\Log\Logger::INFO,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'processors' => [
                'backtrace' => [
                    'name' => \Laminas\Log\Processor\Backtrace::class,
                ],
            ],
        ],
    ],
    'router' => [
        'routes' => [
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'account'     => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/user[/:username]',
                    'constraints' => [
                        'username' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ],
                    'defaults' => [
                        'controller' => Controller\AccountParserController::class,
                        'action'     => 'index',
                        'username'   => 'zend'
                    ],
                ],
            ],
            'post'     => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/post[/:post_id]',
                    'constraints' => [
                        'post_id'     => '[0-9]+'
                    ],
                    'defaults' => [
                        'controller' => Controller\PostParserController::class,
                        'action'     => 'index',
                        'post_id' => '0'
                    ],
                ],
            ],
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\AccountParserController::class => WebPageFactory::class,
            Controller\PostParserController::class => WebPageFactory::class,
        ],
    ],
    'controller_plugins' => [
        'factories' => [
            DomExtender::class => InvokableFactory::class,
        ],
        'aliases' => [
            'dom' => DomExtender::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    'service_manager' => [
        'abstract_factories' => [
            \Laminas\Log\LoggerAbstractServiceFactory::class,
        ],
        'factories'          => [
            WebPage::class => WebPageModelFactory::class,
        ],
        'aliases'            => [
            WebPageModelFactory::class => WebPage::class,
        ],
    ],
];
