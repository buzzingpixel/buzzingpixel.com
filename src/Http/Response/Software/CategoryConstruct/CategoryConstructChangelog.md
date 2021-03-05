# Construct for ExpressionEngine Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## 2.2.0 - 2017-07-31
### New
- Added new `{construct:has_grandchildren}` variable
### Fixed
- Fixed an issue where the tag pair might fail to retrieve categories if database return types were not set up correctly

## 2.1.1 - 2016-12-20
### Fixed
- Fixed an issue where the tag pair would not return results with EE 3.4.4 or newer due to a model property typing issue

## 2.1.0 - 2016-01-14
### New
- Added new variables with parent information to each level: `{construct:parent_l1:var_name}` and `{construct:parent_l2:var_name}` and so on
- Added new tag parameter `parent_id_with_children="2|4|6"`
### Fixed
- Fixed a bug where the `direct_parent` tag parameter would not accept a pipe delimted list of parent IDs
- Fixed a bug where, under certain circumstances, categories might not be ordered correctly
- Fixed a bug where category image directories would not be parsed

## 2.0.0 - 2015-10-27
This version completely removes support for ExpressionEngine 2. For a limited time, the previous version for EE 2 is available in the zip file when you download. Because the control panel component is no longer necessary in EE 3 and Category Construct is only about the tags now, the add-on type has changed from Module to Plugin. When updating from EE 2 to 3, it is recommended that you uninstall the EE 2 version, update ExpressionEngine, then install the new version of Category Construct.
### Breaking
- Category Construct 2.0.0 is not compatible at all with ExpressionEngine 2
- Removed control panel component and changed add-on type to plugin
### New
- Compatible with ExpressionEngine 3
- Added an overall `count` variable that does not factor in nesting
- Added an overall `total_results` variable that does not factor in nesting
### Updated
- Use ExpressionEngine 3 models to retrieve all category data
- Custom fields are now always available within the tag pair

## 1.2.0 - 2015-08-19
### New
- Added new `entry_count="true"` param which will add a `{construct:entry_count}` variable to the tag pair
- Added new parameters `status` and `channel_id` to work with both the `show_empty` parameter and the new `entry_count` parameters
### Updated
- Updated all forms to stop using deprecated EE methods for CSRF protection
- Updated the warning style when no license key has been entered to be friendlier
- Updated language file support throughout

## 1.1.2 - 2015-08-03
### Fixed
- Fixed an error that would occur when adding a category to an empty group

## 1.1.1 - 2015-06-29
### Fixed
- Fixed a bug where an error could occur when calling the `category_save` hook

## 1.1.0 - 2015-04-21
### New
- Added the ability to get custom fields in the output tag
### Fixed
- Fixed a bug where the expected category data was not sent to the `category_save` hook

## 1.0.0 - 2015-04-07
This is the initial release of Category Construct.
### Features
- Easily manage, order, and next Categories in the ExpressionEngine Control Panel
- Powerful template tags
