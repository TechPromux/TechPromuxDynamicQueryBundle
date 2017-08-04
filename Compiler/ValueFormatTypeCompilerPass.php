<?php

namespace TechPromux\DynamicQueryBundle\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;


class ValueFormatTypeCompilerPass implements CompilerPassInterface
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
            'techpromux_dynamic_query.type.value_format'
        );

        foreach ($taggedServicesIds as $id => $tags) {
            //$type = $container->getDefinition($id);
            $managerDefinition->addMethodCall(
                    'addValueFormatType', array(new \Symfony\Component\DependencyInjection\Reference($id)));

        }
    }

}
