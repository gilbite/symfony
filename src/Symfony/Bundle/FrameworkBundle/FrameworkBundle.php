<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle;

use Symfony\Bundle\FrameworkBundle\DependencyInjection\Compiler\AddConstraintValidatorsPass;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\Compiler\TemplatingPass;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\Compiler\RegisterKernelListenersPass;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\Compiler\AddSecurityVotersPass;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\Compiler\ConverterManagerPass;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\Compiler\RoutingResolverPass;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\Compiler\ProfilerPass;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\Compiler\AddClassesToCachePass;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\Compiler\TranslatorPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\Form\FormConfiguration;

/**
 * Bundle.
 *
 * @author Fabien Potencier <fabien.potencier@symfony-project.com>
 */
class FrameworkBundle extends Bundle
{
    /**
     * Boots the Bundle.
     */
    public function boot()
    {
        if ($this->container->has('error_handler')) {
            $this->container->get('error_handler');
        }

        FormConfiguration::clearDefaultCsrfSecrets();

        if ($this->container->hasParameter('csrf_secret')) {
            FormConfiguration::addDefaultCsrfSecret($this->container->getParameter('csrf_secret'));
            FormConfiguration::enableDefaultCsrfProtection();
        }

        // the session ID should always be included in the CSRF token, even
        // if default CSRF protection is not enabled
        if ($this->container->has('session')) {
            $container = $this->container;
            FormConfiguration::addDefaultCsrfSecret(function () use ($container) {
                // automatically starts the session when the CSRF token is
                // generated
                $container->get('session')->start();

                return $container->get('session')->getId();
            });
        }
    }

    public function registerExtensions(ContainerBuilder $container)
    {
        parent::registerExtensions($container);

        $container->addCompilerPass(new AddSecurityVotersPass());
        $container->addCompilerPass(new ConverterManagerPass());
        $container->addCompilerPass(new RoutingResolverPass());
        $container->addCompilerPass(new ProfilerPass());
        $container->addCompilerPass(new RegisterKernelListenersPass());
        $container->addCompilerPass(new TemplatingPass());
        $container->addCompilerPass(new AddConstraintValidatorsPass());
        $container->addCompilerPass(new AddClassesToCachePass());
        $container->addCompilerPass(new TranslatorPass());
    }
}
