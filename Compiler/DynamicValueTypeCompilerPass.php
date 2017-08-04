<?php

namespace TechPromux\DynamicQueryBundle\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;


class DynamicValueTypeCompilerPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('techpromux_dynamic_query.manager.util_dynamic_query')) {
            return;
        }

        $managerDefinition = $container->getDefinition(
            'techpromux_dynamic_query.manager.util_dynamic_query'
        );

        $taggedServicesIds = $container->findTaggedServiceIds(
            'techpromux_dynamic_query.type.dynamic_value'
        );

        foreach ($taggedServicesIds as $id => $tags) {
            //$type = $container->getDefinition($id);
            $managerDefinition->addMethodCall(
                    'addDynamicValueType', array(new \Symfony\Component\DependencyInjection\Reference($id)));

        }
    }

}
