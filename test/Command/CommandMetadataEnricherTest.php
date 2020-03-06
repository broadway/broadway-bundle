<?php

declare(strict_types=1);

/*
 * This file is part of the broadway/broadway-bundle package.
 *
 * (c) 2020 Broadway project
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Broadway\Bundle\BroadwayBundle\Command;

use Broadway\Domain\Metadata;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CommandMetadataEnricherTest extends TestCase
{
    private $command;
    private $input;
    private $event;
    private $enricher;
    private $metadata;

    public function setUp(): void
    {
        $this->command = new MyCommand();
        $this->arguments = 'broadway:test:command argument --option=true --env=dev';

        $this->input = $this->createMock('Symfony\Component\Console\Input\ArgvInput');
        $this->input->expects($this->any())
            ->method('__toString')
            ->will($this->returnValue($this->arguments));

        $output = $this->createMock('Symfony\Component\Console\Output\OutputInterface');

        $this->event = new ConsoleCommandEvent($this->command, $this->input, $output);
        $this->enricher = new CommandMetadataEnricher();
        $this->metadata = new Metadata(['yolo' => 'bam']);
    }

    /**
     * @test
     */
    public function it_adds_the_command_class_and_arguments()
    {
        $this->enricher->handleConsoleCommandEvent($this->event);

        $expected = $this->metadata->merge(new Metadata([
            'console' => [
                'command' => 'Broadway\Bundle\BroadwayBundle\Command\MyCommand',
                'arguments' => $this->arguments,
            ],
        ]));

        $actual = $this->enricher->enrich($this->metadata);
        $this->assertEquals($expected, $actual);
    }
}

class MyCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('broadway:test:command')
            ->setDescription('This is a test command');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return;
    }
}
