<?php
/**
 * RouteAliasCollection.php
 * symfony3
 * Date: 12.06.16
 */

namespace Avanzu\AdminThemeBundle\Routing;

use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\Resource\ResourceInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;

class RouteAliasCollection
{

    /**
     * RouteAliasCollection constructor.
     */
    public function __construct(
        protected string $cacheDir,
        protected RouterInterface $router,
        protected string $optionName,
        protected string $env,
        private bool $debug,
        protected ?array $routeAliases = null
    ){
    }

    /**
     * @return string
     */
    protected function getCacheFileName(): string
    {
        return sprintf(
            '%s/AliasRoutes/%s%s.php',
            $this->cacheDir,
            ucfirst($this->env),
            Container::camelize($this->optionName)
        );
    }

    /**
     * @return ResourceInterface[]
     */
    public function getResources(): array
    {
        return $this->router->getRouteCollection()->getResources();
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function hasAlias($name): bool
    {
        $aliases = $this->getAliases();
        return isset($aliases[$name]);
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function getRouteByAlias($name): mixed
    {
        $aliases = $this->getAliases();
        return isset($aliases[$name]) ? $aliases[$name] : NULL;
    }

    /**
     * @return array|mixed
     */
    public function getAliases(): mixed
    {
        if (!is_null($this->routeAliases)) {
            return $this->routeAliases;
        }

        $cache = new ConfigCache($this->getCacheFileName(), $this->debug);
        if  ($cache->isFresh()) {
            $this->routeAliases = unserialize(file_get_contents($cache->getPath()));

            return $this->routeAliases;
        }

        $this->routeAliases = $this->loadRoutes();
        $cache->write(serialize($this->routeAliases), $this->getResources());
        return $this->routeAliases;
    }

    /**
     * @return array
     */
    protected function loadRoutes(): array
    {
        $aliases = [];
        foreach($this->router->getRouteCollection()->all() as $name => $candidate) {
            if(!$this->hasConfiguredOption($candidate)) {
                continue;
            }

            $aliases[$candidate->getOption($this->optionName)] = $name;
        }
        return $aliases;
    }

    /**
     * @param Route $route
     *
     * @return bool
     */
    public function hasConfiguredOption(Route $route): bool
    {
        if(!$route->hasOption($this->optionName)) {
            return false;
        }
        return true;
    }
}
