<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Commands;

use Illuminate\Console\Command;
use Lemaur\Pinterest\Facades\Pinterest;

class PinterestGetAccessCodeLinkCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'pinterest:get-access-code-link';

    /**
     * @var string
     */
    protected $description = "Generate the link to get the Pinterest's access code.";

    public function handle(): int
    {
        $link = Pinterest::oauth()->getAccessCodeLink();

        $this->line('Copy/paste this link into your browser and follow the instructions provided.');
        $this->newLine();

        $this->info($link);
        $this->newLine();

        return self::SUCCESS;
    }
}
