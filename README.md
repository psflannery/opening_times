Opening Times
===

The theme for the main Opening Times website.
http://otdac.org/

Version: 1.3.8
Date: 24/8/15

Contains the main theme files responsible for presentation. All theme agnostic functionality like custom post-types and taxanomies are located in a separate plugin.

This version of the theme is intended to address perfomance in both the front and backend. Much of the code has been optimised and tidied and is now structured in a way to allow for quick implementation of any future additions or customisations.
This version can be used for production, but there are a few known, but edge case bugs realting to seaching and the 404 page.

List of additions and theme features in no particular order...

* Retina images - @x2 images size added and automatically created upon upload. Retina.js used to serve the correct size based on screen resolution.
* Lazyload iframes - with so many video going into the Reading section, page loading time was being effected. Videos now only load when the parent accordion is opened.
* Ajaxified - Pages loaded via ajax so that the Opening Times logo spin animation doesn't get interupted as you navigate through the site. Using History.js to update permalinks and keep the back button working.
* Extra Accordion callbacks - closing an accordion will now only reset its child iframe, not every single one on the page. 
* Mobile performance - off canvas menu performs a little more smoothly and resizes properly when changing the window size.
* Customizer - Dropdowns and menu controls now set in the theme customizer.
* Code scrub - reorganised file structure, made sure things were properly escaped. Yawn :P

To Do
-----
* Open parent accordion when child is accessed via a permalink. On older Issues, the reading article accordion still opens, but the parent doesn't. Am THIS close to getting it sort, but am also THIS far.
* Update the active menu with an `.active` class.
* Sort out permalink issues when searching from anything other than the home page.
* Tidyup template and .js for the 404 page to make it work better with History.js
