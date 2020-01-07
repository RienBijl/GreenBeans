<?php

namespace GreenBeans\Console\Commands\Brew;

use GreenBeans\Console\Command;

use function GreenBeans\Util\Sanitize\alphanum;

class Provider extends Command
{

    /**
     * @inheritDoc
     */
    public function run(array $args): void
    {
        if (!isset($args[2])) {
            parent::error("You did not properly specify a provider-name");
        } else {
            $provider = alphanum($args[2]);
            $path = __DIR__ . '/../../../../app/Providers/' . $provider . '.php';
            if (file_exists($path)) {
                parent::error("This provider already exists");
            } else {
                $file = fopen($path, "w") or parent::warn("Cannot generate provider, try chmod") and exit;
                fwrite($file, $this->genContent($provider)) or parent::warn("Could not supply content, try chmod") and exit;
                fclose($file) or parent::warn("Could not close file link") and exit;
                parent::msg("Successfully made " . $provider . ' at ' . realpath($path));
            }
        }
    }

    /**
     * Generate the content for the file
     * @param string $name
     * @return string
     */
    private function genContent(string $name): string
    {
        return <<<EOT
<?php

namespace App\Providers;

use GreenBeans\Base\Provider;

class $name extends Provider {

    public function register()
    {
        // Register your data
    }

}

EOT;
    }

    /**
     * @inheritDoc
     */
    public static function invoke(array $args): void
    {
        (new self())->run($args);
    }
}