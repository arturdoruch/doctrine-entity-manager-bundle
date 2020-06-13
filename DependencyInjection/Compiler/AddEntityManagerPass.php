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
        $registryServiceId = 'arturdoruch.doctrine_entity_manager_registry';
        $registryDefinition = $container->getDefinition($registryServiceId);
        $managerServiceIds = $container->findTaggedServiceIds('arturdoruch.doctrine_entity_manager', true);

        foreach ($managerServiceIds as $id => $attributes) {
            $managerDefinition = $container->getDefinition($id);
            $managerDefinition->addMethodCall('construct', [new Reference($registryServiceId)]);
            $registryDefinition->addMethodCall('register', [new Reference($id)]);
        }
    }
}
