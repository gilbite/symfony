<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class TemplatingPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('templating')) {
            return;
        }

        if ($container->hasDefinition('templating.engine.php')) {
            $helpers = array();
            foreach ($container->findTaggedServiceIds('templating.helper') as $id => $attributes) {
                if (isset($attributes[0]['alias'])) {
                    $helpers[$attributes[0]['alias']] = $id;
                }
            }

            $definition = $container->getDefinition('templating.engine.php');
            $arguments = $definition->getArguments();
            $definition->setArguments($arguments);

            if (count($helpers) > 0) {
                $definition->addMethodCall('setHelpers', array($helpers));
            }
        }

        if ($container->hasDefinition('templating.engine.delegating')) {
            $queue = new \SplPriorityQueue();
            $order = PHP_INT_MAX;
            foreach ($container->findTaggedServiceIds('templating.engine') as $id => $attributes) {
                $queue->insert($id, array(isset($attributes[0]['priority']) ? $attributes[0]['priority'] : 0, --$order));
            }

            $engines = array();
            foreach ($queue as $engine) {
                $engines[] = $engine;
            }

            $container->getDefinition('templating.engine.delegating')->addMethodCall('setEngineIds', array($engines));
        }
    }
}
