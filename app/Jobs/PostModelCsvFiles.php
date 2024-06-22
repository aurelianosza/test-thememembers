<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PostModelCsvFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $filename;
    public string $model;

    public function __construct(string $filename, string $model)
    {
        $this->filename = $filename;
        $this->model    = $model;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $file = fopen(storage_path('app/models/csv_files/'. $this->filename), 'r');

        $rows = [];
        $pivot = 0;

        $line = fgets($file);

        $structure = explode(',', str_replace(PHP_EOL, '', $line));


        while(!feof($file))
        {
            $line = fgets($file);

            $modelValues = explode(',', str_replace(PHP_EOL, '', $line), count($structure));

            if(count($modelValues) != count($structure))
            {
                continue;
            }

            $rows[] =  $modelValues;
        }

        $this->model::insert([
            ...array_map(function($currentData) use ($structure) {

                return array_combine($structure, $currentData);

            }, $rows)
        ]);
    }
}
