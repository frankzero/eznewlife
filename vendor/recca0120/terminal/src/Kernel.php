<?php

namespace Recca0120\Terminal;

use Illuminate\Contracts\Console\Kernel as KernelContract;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Foundation\Console\QueuedJob;
use Recca0120\Terminal\Application as Artisan;

class Kernel implements KernelContract
{
    /**
     * The Artisan application instance.
     *
     * @var \Illuminate\Console\Application
     */
    protected $artisan;

    /**
     * The Artisan commands provided by the application.
     *
     * @var array
     */
    protected $commands = [];

    /**
     * Create a new console kernel instance.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @param \Illuminate\Contracts\Events\Dispatcher      $events
     */
    public function __construct(Artisan $artisan)
    {
        $this->artisan = $artisan;
    }

    /**
     * Handle an incoming console command.
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function handle($input, $output = null)
    {
        return $this->artisan->run($input, $output);
    }

    /**
     * Run an Artisan console command by name.
     *
     * @param string $command
     * @param array  $parameters
     *
     * @return int
     */
    public function call($command, array $parameters = [])
    {
        return $this->artisan->call($command, $parameters);
    }

    /**
     * Queue an Artisan console command by name.
     *
     * @param string $command
     * @param array  $parameters
     *
     * @return int
     */
    public function queue($command, array $parameters = [])
    {
        $app = $this->artisan->getLaravel();
        $app[Queue::class]->push(
            QueuedJob::class, func_get_args()
        );
    }

    /**
     * Get all of the commands registered with the console.
     *
     * @return array
     */
    public function all()
    {
        return $this->artisan->all();
    }

    /**
     * Get the output for the last run command.
     *
     * @return string
     */
    public function output()
    {
        return $this->artisan->output();
    }
}
