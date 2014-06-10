<?php

namespace Bluetea\ImportBundle;

use Bluetea\ImportBundle\DependencyInjection\Compiler\ImportCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BlueteaImportBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ImportCompilerPass());
    }
}
