<?php
/**
 * RouteAliasCollection.php
 * symfony3
 * Date: 12.06.16
 */

namespace Avanzu\AdminThemeBundle\Routing;

use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;

class RouteAliasCollection
{
    /**
     * @var string
     */
    protected string $cacheDir;

    /**
     * @var RouterInterface
     */
    protected RouterInterface $router;

    /**
     * @var array|null
     */
    protected ?array $routeAliases = null;

    /**
     * @var string
     */
    protected string $optionName;

    /**
     * @var string
     */
    protected string $env;

    /**
     * @var boolean
     */
    private bool $debug;

    /**
     * RouteAliasCollection constructor.
     *
     * @param                 $cacheDir
     * @param RouterInterface $router
     * @param                 $optionName
     * @param                 $env
     * @param                 $debug
     */
    public function __construct($cacheDir, RouterInterface $router, $optionName, $env, $debug)
    {
        $this->cacheDir = $cacheDir;
        $this->router = $router;
        $this->optionName = $optionName;
        $this->debug = $debug;
        $this->env = $env;
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
     * @return \Symfony\Component\Config\Resource\ResourceInterface[]
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
    public function getRouteByAlias($name)
    {
        $aliases = $this->getAliases();
        return isset($aliases[$name]) ? $aliases[$name] : NULL;
    }

    /**
     * @return array|mixed
     */
    public function getAliases()
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
