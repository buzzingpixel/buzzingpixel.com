# Treasury for ExpressionEngine Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## 1.1.2 - 2020-12-20
### Fixed
- Fixed display issues with locations view filters and bulk actions in EE 6
- Fixed an EE 6 pagination display issue

## 1.1.1 - 2018-03-03
### Fixed
- Fixed an issue where the add-file buttons might not work right away

## 1.1.0 - 2017-11-04
### New
- Added compatibility with EE 4
- Added field type compatibility with EE 4's new Fluid Field
- Added field type compatibility with Grid
- Added field type compatibility with Bloqs
### Fixed
- Fixed JavaScript errors happening on the JavaScript console
- Fixed a huge massive gaping bug where Low Variables template tags for the Treasury field type would not output anything (uhh, so, I think I might have been on drugs when I wrote that or something? I just don’t know about me sometimes…)

## 1.0.5 - 2017-08-29
### Added
- Added server path reference to location path for local locations
### Fixed
- Fixed an issue where uploads to Amazon S3 would not set the correct mime content type

## 1.0.4 - 2017-01-16
### Fixed
- Fixed a minor incompatibility that would throw warnings in PHP 7.1

## 1.0.3 - 2016-10-11
### Changed
- Treasury now only loads the AWS library if it is not already loaded
### Fixed
- Fixed a validation error if a field type is set to image only
- Fixed an issue with input validation after a field fails validation
- Fixed a bug where the tag may return the most recent images uploaded to Treasury when the field has no image selected

## 1.0.2 - 2016-07-13
### Fixed
- Fixed a bug where searching a location would actually search all locations
- Fixed an issue where URLs for file names with spaces would not be properly URL encoded
- Fixed a bug where a PHP error might occur when using the FilesTag in versions of PHP less than 5.6
- Fixed a bug where Treasury settings could not be accessed or saved
- Fixed a bug where sidebar items after locations would not show if there were no locations

## 1.0.1 - 2016-07-06
### Fixed
- Fixed an error that could occur in PHP 5.5 when uploading a file
- Fixed a PHP warning that would occur if there was an error parsing the update feed on the Treasury updates page in the Control Panel

## 1.0.0 - 2016-07-01
This is the initial release of Treasury for ExpressionEngine.

Treasury allows you to store files on Amazon S3 or an FTP/SFTP server.
