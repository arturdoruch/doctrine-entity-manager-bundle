<?php

namespace ArturDoruch\DoctrineEntityManagerBundle;

use ArturDoruch\DoctrineEntityManagerBundle\DependencyInjection\Compiler\AddEntityManagerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Artur Doruch <arturdoruch@interia.pl>
 */
class ArturDoruchDoctrineEntityManagerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AddEntityManagerPass());
    }
}
