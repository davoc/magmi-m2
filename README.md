Magmi 2 for Magento 2.1.x and 2.2.x
===================================

This is fork from official magmi Github reposiotry (https://github.com/dweeves/magmi-git). 
This fork use version 0.7.23 of magmi with changes for Magento 2 imported from repositories:
- tagesjump/magmi-m2 - https://github.com/tagesjump/magmi-m2
- pushnov-i/magmi-m2 - https://github.com/pushnov-i/magmi-m2
On top of that custom compatibility fixes were added.

We're accepting pull requests.
''''''''''''''''''''''''''''''

Magento CE 2 Support
====================

Current version is in **beta** and tested only for import simple and configurable products, categories, images and simple-configurable links.

**NOTICE: If you want to create URL rewrites please enable "On the fly indexer" plugin!**

**Known working plugins:**
- On the fly category creator/importer
- On the fly indexer
- Configurable Item processor
- Image attributes processor 

## Composer Usage ##

1. Copy the magmi directory into the root of your Magento 2 installation.

2. Add the following to the autoload section of your composer.json:
```
"autoload": {
        "psr-4": {
            ...
	    "Magmi\\": "magmi/"
	    ...
        }
}
```

You can then create your own command line script to import and export using the Magmi engine.

