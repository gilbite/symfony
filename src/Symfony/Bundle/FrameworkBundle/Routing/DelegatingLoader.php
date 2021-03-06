<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\Routing;

use Symfony\Bundle\FrameworkBundle\Controller\ControllerNameConverter;
use Symfony\Component\Routing\Loader\DelegatingLoader as BaseDelegatingLoader;
use Symfony\Component\Routing\Loader\LoaderResolverInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

/**
 * DelegatingLoader delegates route loading to other loaders using a loader resolver.
 *
 * This implementation resolves the _controller attribute from the short notation
 * to the fully-qualified form (from a:b:c to class:method).
 *
 * @author Fabien Potencier <fabien.potencier@symfony-project.com>
 */
class DelegatingLoader extends BaseDelegatingLoader
{
    protected $converter;
    protected $logger;

    /**
     * Constructor.
     *
     * @param ControllerNameConverter $converter A ControllerNameConverter instance
     * @param LoggerInterface         $logger    A LoggerInterface instance
     * @param LoaderResolverInterface $resolver  A LoaderResolverInterface instance
     */
    public function __construct(ControllerNameConverter $converter, LoggerInterface $logger = null, LoaderResolverInterface $resolver)
    {
        $this->converter = $converter;
        $this->logger = $logger;

        parent::__construct($resolver);
    }

    /**
     * Loads a resource.
     *
     * @param mixed  $resource A resource
     * @param string $type     The resource type
     *
     * @return RouteCollection A RouteCollection instance
     */
    public function load($resource, $type = null)
    {
        $collection = parent::load($resource, $type);

        foreach ($collection->all() as $name => $route) {
            if ($controller = $route->getDefault('_controller')) {
                try {
                    $controller = $this->converter->fromShortNotation($controller);
                } catch (\Exception $e) {
                    // unable to optimize unknown notation
                }

                $route->setDefault('_controller', $controller);
            }
        }

        return $collection;
    }
}
