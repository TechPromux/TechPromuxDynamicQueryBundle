<?php

namespace TechPromux\Bundle\DynamicQueryBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use TechPromux\Bundle\DynamicQueryBundle\Compiler\TableRelationTypeCompilerPass;

class TechPromuxDynamicQueryBundle extends Bundle
{
    public function build(ContainerBuilder $container) {
        parent::build($container);
        $container->addCompilerPass(new TableRelationTypeCompilerPass());
    }
}
