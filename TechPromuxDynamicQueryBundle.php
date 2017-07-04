<?php

namespace TechPromux\Bundle\DynamicQueryBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use TechPromux\Bundle\DynamicQueryBundle\Compiler\ConditionalOperatorTypeCompilerPass;
use TechPromux\Bundle\DynamicQueryBundle\Compiler\DynamicValueTypeCompilerPass;
use TechPromux\Bundle\DynamicQueryBundle\Compiler\FieldFunctionTypeCompilerPass;
use TechPromux\Bundle\DynamicQueryBundle\Compiler\TableRelationTypeCompilerPass;
use TechPromux\Bundle\DynamicQueryBundle\Compiler\ValueFormatTypeCompilerPass;

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
