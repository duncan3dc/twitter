<?php

namespace duncan3dc\Twitter\Commands;

use duncan3dc\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InstagramCommand extends Command
{
    protected function configure()
    {
        $this
            ->setDescription("Get all the speakers on the current network");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}
