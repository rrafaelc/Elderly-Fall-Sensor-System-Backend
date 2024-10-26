<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    // Registra seus comandos aqui
    protected $commands = [
        \App\Console\Commands\ListenToMQTT::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Defina suas tarefas agendadas aqui
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        // Outros comandos padrão
        require base_path('routes/console.php');
    }
}
