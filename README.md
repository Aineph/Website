# Website
This is the source code for my personal website, written in PHP using the framework Symfony.

# Summary
* Build system
* Code conventions
    * HTML
        * Files
        * Bootstrap
        * Identifiers
        * Classes
        * Translations
    * CSS
    * JS
    * PHP
* Testing

# Build system
This section is not yet completed.
It will be available in a future release.

# Code conventions
This section indicated the rules that have been followed for the integration of this website.

## HTML
### Files
As this project runs with Symfony, the template engine used for this application is Twig.
Every integration file is located in the folder named "**template**".
The files have to be named under the following syntax:

```
[controller_name]/[section_name].html.twig
```

Here, *controller_name* corresponds to the name of the controller (for example **blog** or **security**) and
*section_name* corresponds to the section name (such as **header** or **footer**).

### Bootstrap
Every element should be wrapped into containers, rows and columns.
Final text elements have to be declared under final tags (for example *<p>* or *<a>*, and not *<div>*).

### Identifiers
Every identifier has to be prefixed by the name of the twig file.
For example, if we want to give an identifier for a link that redirects to the homepage in a file named
"**navigation.twig.html**", a good identifier would be declared like the following example:

```
<a id="navigation-home">Home</a>
```

### Classes
The naming rules that applies to identifiers also applies to custom classes.

For each element, the order of classes assignment have to follow the following order:
* Purpose
* Sizing
* Colours
* Positioning
* Custom classes

The custom classes have to appear at the end of the class attribute.
If we take a simple button element, a correct way to declare it will look similar as:

```
<div class="btn btn-lg btn-primary p-5 navigation-button">Button</div>
```

### Translations
The translation files can be found in the folder named "**translations**".
It contains every translation for a specific language, following the XML format.

Every translation name has to follow this naming syntax:

```
[controller_name].[section_name].[name]
```

Here, *controller_name* corresponds to the name of the controller, *section_name* corresponds to the name of the
section, and name will be the chosen name for the translation.

## CSS
This section is not yet completed.
It will be available in a future release.

## JS
This section is not yet completed.
It will be available in a future release.

## PHP
This section is not yet completed.
It will be available in a future release.

# Testing
This section is not yet completed.
It will be available in a future release.
