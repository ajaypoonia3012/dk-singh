<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Services\Media\MediaLinker;

use App\Models\Service;
use App\Models\Program;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\Transformation;

class LinkMedia extends Command
{
    protected $signature = 'media:link {type=services}';

    protected $description = 'Link existing image paths to Media records';

    public function handle(MediaLinker $linker): int
    {
        $type = strtolower($this->argument('type'));

        switch ($type) {

            case 'services':

                $count = $linker->linkCollection(
                    Service::whereNull('media_id')
                        ->whereNotNull('image')
                );

                $this->info("Linked {$count} service record(s).");

                break;

            case 'programs':

                $count = $linker->linkCollection(
                    Program::whereNull('media_id')
                        ->whereNotNull('image')
                );

                $this->info("Linked {$count} program record(s).");

                break;

            case 'products':

                $count = $linker->linkCollection(
                    Product::whereNull('media_id')
                        ->whereNotNull('image')
                );

                $this->info("Linked {$count} product record(s).");

                break;

            case 'testimonials':

                $count = $linker->linkCollection(
                    Testimonial::whereNull('media_id')
                        ->whereNotNull('image')
                );

                $this->info("Linked {$count} testimonial record(s).");

                break;

            case 'transformations':

                $count = $linker->linkTransformationCollection(
                    Transformation::query()
                );

                $this->info("Linked {$count} transformation image(s).");

                break;

            default:

                $this->error("Unknown type: {$type}");

                return self::FAILURE;
        }

        return self::SUCCESS;
    }
}