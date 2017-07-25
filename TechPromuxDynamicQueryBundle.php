<?php

namespace  TechPromux\DynamicQueryBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use  TechPromux\DynamicQueryBundle\Compiler\ConditionalOperatorTypeCompilerPass;
use  TechPromux\DynamicQueryBundle\Compiler\DynamicValueTypeCompilerPass;
use  TechPromux\DynamicQueryBundle\Compiler\FieldFunctionTypeCompilerPass;
use  TechPromux\DynamicQueryBundle\Compiler\TableRelationTypeCompilerPass;
use  TechPromux\DynamicQueryBundle\Compiler\ValueFormatTypeCompilerPass;

class TechPromuxDynamicQueryBundle extends Bundle
{
    public function build(ContainerBuilder $container) {
        parent::build($container);
        $container->addCompilerPass(new ConditionalOperatorTypeCompilerPass());
        $container->addCompilerPass(new DynamicValueTypeCompilerPass());
        $container->addCompilerPass(new FieldFunctionTypeCompilerPass());
        $container->addCompilerPass(new TableRelationTypeCompilerPass());
        $container->addCompilerPass(new ValueFormatTypeCompilerPass());
    }
}
