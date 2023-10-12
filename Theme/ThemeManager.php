<?php
/**
 * ThemeManager.php
 * publisher
 * Date: 18.04.14
 */

namespace Avanzu\AdminThemeBundle\Theme;

use Avanzu\FoundationBundle\Util\DependencyResolverInterface;

class ThemeManager
{
    protected array $stylesheets = [];

    protected array $javascripts = [];

    protected $resolverClass;

    public function __construct($resolverClass = null)
    {
        $this->resolverClass = $resolverClass ?: 'Avanzu\AdminThemeBundle\Util\DependencyResolver';
    }

    public function registerScript($id, $src, $deps = [], $location = 'bottom'): void
    {
        if (!isset($this->javascripts[$id])) {
            $this->javascripts[$id] = [
                'src' => $src,
                'deps' => $deps,
                'location' => $location,
            ];
        }
    }

    public function registerStyle($id, $src, $deps = []): void
    {
        if(!isset($this->stylesheets[$id])) {
            $this->stylesheets[$id] = [
                'src' => $src,
                'deps' => $deps,
            ];
        }
    }

    public function getScripts($location = 'bottom'): array
    {
        $unsorted = [];
        $srcList = [];

        foreach($this->javascripts as $id => $scriptDefinition) {
            if($scriptDefinition['location'] == $location) {
                $unsorted[$id] = $scriptDefinition;
            }
        }

        $queue = $this->getResolver()->register($unsorted)->resolveAll();
        foreach($queue as $def){
            $srcList[] = $def['src'];
        }

        return $srcList;
    }

    public function getStyles(): array
    {
        $srcList = [];
        $queue = $this->getResolver()->register($this->stylesheets)->resolveAll();
        foreach($queue as $def){
            $srcList[] = $def['src'];
        }

        return $srcList;
    }

    /**
     * @return DependencyResolverInterface
     */
    protected function getResolver(): DependencyResolverInterface
    {
        $class = $this->resolverClass;
        return new $class();
    }
}
