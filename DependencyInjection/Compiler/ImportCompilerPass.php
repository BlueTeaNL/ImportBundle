<?php

namespace Bluetea\ImportBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class ImportCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('bluetea_import.chain')) {
            return;
        }

        $definition = $container->getDefinition(
            'bluetea_import.chain'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'bluetea_import.import_type'
        );

        // Add all import types to the import chain
        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $definition->addMethodCall(
                    'addImportType',
                    array(new Reference($id), $attributes['description'])
                );
            }
        }

        // Force import chain sorting
        $definition->addMethodCall(
            'sortImportChain'
        );
    }
}