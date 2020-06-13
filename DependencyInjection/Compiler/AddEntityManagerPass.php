<?php

namespace ArturDoruch\DoctrineEntityManagerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Artur Doruch <arturdoruch@interia.pl>
 */
class AddEntityManagerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $registryDefinition = $container->getDefinition('arturdoruch.doctrine_entity_manager_registry');
        $managerServiceIds = $container->findTaggedServiceIds('arturdoruch.doctrine_entity_manager', true);

        foreach ($managerServiceIds as $id => $attributes) {
            $registryDefinition->addMethodCall('register', [new Reference($id)]);
        }
    }
}
