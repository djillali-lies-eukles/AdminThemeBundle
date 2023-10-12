<?php
/**
 * MenuItemModel.php
 * avanzu-admin
 * Date: 23.02.14
 */

namespace Avanzu\AdminThemeBundle\Model;

/**
 * Class MenuItemModel
 *
 * @package Avanzu\AdminThemeBundle\Model
 */
class MenuItemModel implements MenuItemInterface
{
    /**
     * @var mixed
     */
    protected $identifier;

    /**
     * @var string
     */
    protected string $label;

    /**
     * @var string
     */
    protected string $route;

    /**
     * @var array
     */
    protected array $routeArgs = [];

    /**
     * @var bool
     */
    protected bool $isActive = false;

    /**
     * @var array
     */
    protected array $children = [];

    /**
     * @var mixed
     */
    protected $icon = false;

    /**
     * @var mixed
     */
    protected $badge = false;

    protected string $badgeColor = 'green';

    /**
     * @var MenuItemInterface|null
     */
    protected ?MenuItemInterface $parent = null;

    public function __construct(
        $id,
        $label,
        $route,
        $routeArgs = [],
        $icon = false,
        $badge = false,
        $badgeColor = 'green'
    ) {
        $this->badge = $badge;
        $this->icon = $icon;
        $this->identifier = $id;
        $this->label = $label;
        $this->route = $route;
        $this->routeArgs = $routeArgs;
        $this->badgeColor = $badgeColor;
    }

    /**
     * @return mixed
     */
    public function getBadge()
    {
        return $this->badge;
    }

    /**
     * @param mixed $badge
     *
     * @return $this
     */
    public function setBadge($badge): self
    {
        $this->badge = $badge;
        return $this;
    }

    /**
     * @return array
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @param array $children
     */
    public function setChildren(array $children): void
    {
        $this->children = $children;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param mixed $icon
     *
     * @return $this
     */
    public function setIcon($icon): self
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param mixed $identifier
     *
     * @return $this
     */
    public function setIdentifier($identifier): self
    {
        $this->identifier = $identifier;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param boolean $isActive
     *
     * @return $this
     */
    public function setIsActive($isActive): self
    {
        if ($this->hasParent()) {
            $this->getParent()->setIsActive($isActive);
        }
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasParent(): bool
    {
        return $this->parent instanceof MenuItemInterface;
    }

    /**
     * @return MenuItemInterface
     */
    public function getParent(): MenuItemInterface
    {
        return $this->parent;
    }

    /**
     * @param MenuItemInterface|null $parent
     *
     * @return $this
     */
    public function setParent(MenuItemInterface $parent = null): self
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     *
     * @return $this
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @param string $route
     *
     * @return $this
     */
    public function setRoute($route): self
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @return array
     */
    public function getRouteArgs(): array
    {
        return $this->routeArgs;
    }

    /**
     * @param array $routeArgs
     *
     * @return $this
     */
    public function setRouteArgs(array $routeArgs): self
    {
        $this->routeArgs = $routeArgs;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasChildren(): bool
    {
        return count($this->children) > 0;
    }

    /**
     * @param MenuItemInterface $child
     *
     * @return $this
     */
    public function addChild(MenuItemInterface $child): self
    {
        $child->setParent($this);
        $this->children[] = $child;
        return $this;
    }

    /**
     * @param MenuItemInterface $child
     *
     * @return $this
     */
    public function removeChild(MenuItemInterface $child): self
    {
        if (false !== ($key = array_search($child, $this->children))) {
            unset($this->children[$key]);
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getBadgeColor(): string
    {
        return $this->badgeColor;
    }

    /**
     * @param string $badgeColor
     *
     * @return $this
     */
    public function setBadgeColor(string $badgeColor): self
    {
        $this->badgeColor = $badgeColor;
        return $this;
    }

    /**
     * @return MenuItemInterface|null
     */
    public function getActiveChild(): ?MenuItemInterface
    {
        foreach ($this->children as $child) {
            if ($child->isActive()) {
                return $child;
            }
        }
        return null;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }
}
