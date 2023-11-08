AdminThemeBundle [![knpbundles.com](http://knpbundles.com/avanzu/AdminThemeBundle/badge-short)](http://knpbundles.com/avanzu/AdminThemeBundle)
================


## Installation

```bash
    composer require avanzu/admin-theme-bundle
```

```bash
    php bin/console assets:install --symlink
```

### Changing default values from templates
If you want to change any default value as for example `admin_skin` all you need to do is define the same at `app/config/config.yml` under `[twig]` section. See example below:

```yaml
# Twig Configuration
twig:
    debug:            "%kernel.debug%"
    strict_variables: "%kernel.debug%"
    globals:
        admin_skin: skin-blue
```

You could also define those values at `app/config/parameters.yml`:

```yaml
admin_skin: skin-blue
```

and then use as follow in `app/config/config.yml`:

```yaml
# Twig Configuration
twig:
    debug:            "%kernel.debug%"
    strict_variables: "%kernel.debug%"
    globals:
        admin_skin: "%admin_skin%"
```

AdminLTE skins are: skin-blue (default for this bundle), skin-blue-light, skin-yellow, skin-yellow-light, skin-green, skin-green-light, skin-purple, skin-purple-light, skin-red, skin-red-light, skin-black and skin-black-light. If you want to know more then go ahead and check docs for AdminLTE [here][1].

There are a few values you could change for sure without need to touch anything at bundle, just take a look under `Resources/views`. That's all.


### Next Steps
* [Using the layout](Resources/docs/layout.md)
* [Rebuilding the assets](Resources/docs/rebuild.md)
* [Using the ThemeManager](Resources/docs/theme_manager.md)
* [Components](Resources/docs/component_events.md)
* [Navbar User](Resources/docs/navbar_user.md)
* [Navbar Tasks](Resources/docs/navbar_tasks.md)
* [Navbar Messages](Resources/docs/navbar_messages.md)
* [Navbar Notifications](Resources/docs/navbar_notifications.md)
* [Sidebar User](Resources/docs/sidebar_user.md)
* [Sidebar Navigation](Resources/docs/sidebar_navigation.md)
* [Breadcrumb Menu](Resources/docs/breadcrumbs.md)
* [Form theme](Resources/docs/form_theme.md)

 [1]: https://almsaeedstudio.com/themes/AdminLTE/documentation/index.html
 [2]: https://img.shields.io/badge/Symfony-%202.x%20&%203.x-green.svg
 [3]: https://github.com/avanzu/AdminThemeBundle/issues?utf8=%E2%9C%93&q=is%3Aopen%20is%3Aissue
