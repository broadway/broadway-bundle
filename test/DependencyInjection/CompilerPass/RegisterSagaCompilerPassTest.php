<?php

/*
 * This file is part of the broadway/broadway package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Broadway\Bundle\BroadwayBundle\DependencyInjection\Configuration\CompilerPass;

use Broadway\Bundle\BroadwayBundle\DependencyInjection\CompilerPass\RegisterSagaCompilerPass;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class RegisterSagaCompilerPassTest extends AbstractCompilerPassTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(
            new RegisterSagaCompilerPass(
                'broadway.saga.multiple_saga_manager',
                'broadway.saga'
            )
        );
    }

    /**
     * @test
     */
    public function it_registers_sagas()
    {
        $sagaManager = new Definition();
        $sagaManager->addArgument('my_saga_state_repository');
        $sagaManager->addArgument([]);
        $this->setDefinition('broadway.saga.multiple_saga_manager', $sagaManager);


        $saga1 = new Definition();
        $saga1->addTag('broadway.saga', [
            'type' => 'saga_1',
        ]);
        $this->setDefinition('my_saga_1', $saga1);

        $saga2 = new Definition();
        $saga2->addTag('broadway.saga', [
            'type' => 'saga_2',
        ]);
        $this->setDefinition('my_saga_2', $saga2);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'broadway.saga.multiple_saga_manager',
            1,
            [
                'saga_1' => new Reference('my_saga_1'),
                'saga_2' => new Reference('my_saga_2'),
            ]
        );
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Tag "broadway.saga" of service "my_saga_1" should have a "type" attribute, indicating the type of saga it represents
     */
    public function it_throws_when_registering_sagas_without_type()
    {
        $sagaManager = new Definition();
        $this->setDefinition('broadway.saga.multiple_saga_manager', $sagaManager);


        $saga1 = new Definition();
        $saga1->addTag('broadway.saga');
        $this->setDefinition('my_saga_1', $saga1);

        $this->compile();
    }
}
