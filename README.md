# SilverWare AGLS Module

[![Latest Stable Version](https://poser.pugx.org/silverware/agls/v/stable)](https://packagist.org/packages/silverware/agls)
[![Latest Unstable Version](https://poser.pugx.org/silverware/agls/v/unstable)](https://packagist.org/packages/silverware/agls)
[![License](https://poser.pugx.org/silverware/agls/license)](https://packagist.org/packages/silverware/agls)

Works in conjunction with [SilverWare][silverware] to add Australian Government [AGLS][agls] metadata to pages.

## Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Issues](#issues)
- [Contribution](#contribution)
- [Maintainers](#maintainers)
- [License](#license)

## Requirements

- [SilverWare][silverware]

## Installation

Installation is via [Composer][composer]:

```
$ composer require silverware/agls
```

## Configuration

As with all SilverStripe modules, configuration is via YAML. Within the `config.yml` file you will
find a series of AGLS schemas and metadata mappings. Everything should work out of
the box, however you can expand upon these items if you wish.

After installation, you should define the corporate name, address and contact number values in the
Settings > SilverWare > AGLS > Creator section.  The values for publisher can also be defined, or
check the box "Same as creator".

You can add additional AGLS tags by defining the appropriate mappings in your config YAML, and by
adding the corresponding properties to your model.

## Usage

AGLS tags are automatically added to a page's metadata using the `PageExtension`. This module
works in conjuction with SilverWare's metadata and site configuration extensions to automatically
populate AGLS tags for pages within your site tree.

## Issues

Please use the [issue tracker][issues] for bug reports and feature requests.

## Contribution

Your contributions are gladly welcomed to help make this project better.
Please see [contributing](CONTRIBUTING.md) for more information.

## Maintainers

[![Colin Tucker](https://avatars3.githubusercontent.com/u/1853705?s=144)](https://github.com/colintucker) | [![Praxis Interactive](https://avatars2.githubusercontent.com/u/1782612?s=144)](https://www.praxis.net.au)
---|---
[Colin Tucker](https://github.com/colintucker) | [Praxis Interactive](https://www.praxis.net.au)

## License

[BSD-3-Clause](LICENSE.md) &copy; Praxis Interactive

[silverware]: https://github.com/praxisnetau/silverware
[composer]: https://getcomposer.org
[agls]: http://www.agls.gov.au
[issues]: https://github.com/praxisnetau/silverware-agls/issues
