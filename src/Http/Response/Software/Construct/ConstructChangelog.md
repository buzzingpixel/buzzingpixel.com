# Construct for ExpressionEngine Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## 2.2.0 - 2020-12-19
### New
- Added support for ExpressionEngine 6
### Fixed
- Fixed an issue in ExpressionEngine 6 caused by Member Groups changing to Roles
- Fixed an issue where the node actions menu would not show in EE 6
- Fixed a table overflow issue on the manage trees page
- Fixed an issue with node edit icons not showing in EE 6
- Fixed sort confirm dialog for EE 6
- Fixed a table overflow issue on the template preferences page

## 2.1.1 - 2018-10-11
### Fixed
- Fixed an issue where nodes could not be deleted in ExpressionEngine 4

## 2.1.0 - 2016-08-11
### New
- Added custom menu functionality (EE 3.4.0 or higher)
### Fixed
- Fixed an issue with re-post when saving settings
- Fixed an issue with category pagination

## 2.0.3 - 2016-04-18
### Fixed
- Fixed a bug where if no level 1 items were in the tag output, there was no tag output at all
- Fixed an issue where the `node_slug` tag parameter would filter on the wrong item

## 2.0.2 - 2016-04-15
## Fixed
- Fixed a bug where a Construct Template's channels and listing channels could not be emptied after setting and saving any options once.

## 2.0.1 - 2016-01-19
We'll call this the facepalm release
![Facepalm](https://www.buzzingpixel.com/uploads-static/general/picard-facepalm.jpg)
### Fixed
- Fixed an issue where control panel views might not load

## 2.0.0 - 2016-01-18
Construct 2.0.0 is a major re-write for ExpressionEngine 3. It will not work at all with ExpressionEngine 2. However, the EE 2 version will remain in the downloadable zip file for a few versions so that if you need to use Construct with EE 2 you can do that. Construct 1.x will remain feature frozen even if Construct 2.x advances.
### Breaking
- Construct 2.0.0 is not compatible at all with ExpressionEngine 2.x
### New
- Compatibility with ExpressionEngine 3
- Construct control panel adopts the native look and feel of the ExpressionEngine 3 control panel
- Added the ability to re-order template preferences
- Added options for associating listing channels with a Template Preference
- Added options for marking a template as capable of listing entries
- Added options for marking a template as capable for listing categories
- Added ability to reorder trees
- Added options to nodes for listing channels, pagination, listing entry templates, and category listing templates
- Added ability to expand and collapse Nodes to keep the Nodes page from becoming too cluttered to manage
- Updated drag and drop reordering of Nodes to require user interaction to save rather than auto-saving — this allows changes to the menu items to be queued then saved and updated when the user is ready
- Added the ability to Nodes to have external links
- Updated the setting for disabling routing so that all items not relevant to Nodes are hidden when routing is not enabled — this allows Construct to be used as powerful a menu generation tool only
- Added a new feature that allows routing to be controlled from the EE config file
- Added new variables on the Construct route: `{construct_route:node_parent_id}`, `{construct_route:node_level}`, `{construct_route:node_external_link}`, `{construct_route:node_output}`, `{construct_route:node_pagination}`, `{construct_route:node_pagination_amount}`, `{construct_route:node_listing_channels}`
- Added `menu_output_only="false"` parameter to the Nodes tag
- Added `node_entry_id_not_empty="true"` parameter to the Nodes tag
- Added new variables to the Nodes tag: `{construct:node_external_link}`, `{construct:node_link}`, `{construct:node_routing}`, `{construct:node_pagination}`, `{construct:node_pagination_amount}`, `{construct:node_listing_channels}`, `{construct:node_output}`, `{construct:level_index}`, `{construct:index}`, `{construct:count}`, `{construct:total_results}`
### Deprecated
- The Entry IDs Tag Pair has been deprecated and will be removed in the next major version of Construct. Please use the `{exp:construct:nodes}` tag

## 1.3.0 - 2015-09-10
### New
- The `{exp:construct:breadcrumbs}` tag now supports `{construct:breadcrumb_count}` and `{construct:breadcrumb_total_results}` variables
### Fixed
- Fixed a bug where other add-ons might trigger errors in the Construct field type

## 1.2.0 - 2015-08-19
### New
- Added `menu_output_only="false"` parameter override to output all nodes regardless if menu output is checked in CP
- Added ability to delete a node/page from the field type
### Updated
- Updated all forms to stop using deprecated EE methods for CSRF protection
- Updated the warning style when no license key has been entered to be friendlier
- Updated language file support throughout
### Fixed
- Fixed a PHP error that could occur if an entry was deleted and assigned to a Construct node

## 1.1.0 - 2015-07-11
This release primarily adds a new tag pair to aid in the creation of breadcrumbs or other hierarchical output. Be sure to check out the docs for how it works.
### New
- Added new module tag pair: `{exp:construct:breadcrumbs}`
### Fixed
- Fixed a spelling error in the language file
- Fix a bug where the fieldtype would not work correctly if multiple channels were assigned to a template preference
- Fix a bug where the Construct fieldtype would never validate on submit if field was set to required

## 1.0.1 - 2015-04-13
### Fixed
- Fixed a MySQL error that could happen when installing if MySQL is running in strict mode

## 1.0.0 - 2015-04-07
### Features
- Easy to manage page trees
- Customizable settings
- Powerful template tags
- And much more!
