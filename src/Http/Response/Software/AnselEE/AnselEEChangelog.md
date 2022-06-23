# Ansel for ExpressionEngine Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## 2.3.1 - 2022-06-23
### Fixed
- Fixed an issue where field type initialization may fail in Grid fields in EE 5

## 2.3.0 - 2022-02-12
### Added
- Added support for ExpressionEngine's new Entry Cloning feature

## 2.2.2 - 2022-01-06
### Fixed
- Fixed an issue with field row titles and captions where, if the content contained quote marks, would get cuttoff on display and re-save.

## 2.2.1 - 2021-11-05
### Fixed
- Fixed display issues with Ansel toolbar buttons within blocks fields
- Fixed an issue where Ansel wasn't working correctly in a Bloqs field because Bloqs no longer updates Ansel's row field names
- Fixed an issue with Ansel fields in a cloned Bloq

## 2.2.0 - 2020-12-19
### Added
- Added support for ExpressionEngine 6
### Fixed
- Fixed an issue where EE's classname changes from EllisLab prefix to ExpressionEngine prefix would cause an issue with type-hinting EE classes
- Fixed a positioning issue with jcrop container
- Fixed an issue in EE 6 where the image crop container background would be white instead of transparent
- Fixed issues with fieldtype CSS and icons in EE 6
- Fixed remove field row icon in EE 6
- Fixed field row reordering icon on EE 6

## 2.1.5 - 2018-05-12
### Fixed
- Fixed an issue where crop tool might not be able to scale back down under certain conditions with min width and min height set on a field.

## 2.1.4 - 2018-03-03
### Fixed
- Fixed a bug where Fluid Field tags would return all Ansel images associated with the entry ID

## 2.1.3 - 2018-02-24
### Fixed
- Fixed an issue where an image cropped very small or with corrupt crop data of width/height set to 0/0 would not show a way to edit the image's crop

## 2.1.2 - 2017-12-19
### Fixed
- Fixed an issue with Treasury source files always being reported as missing

## 2.1.1 - 2017-11-09
### Fixed
- Fixed an issue with Treasury and Assets where missing source file might not be detected correctly

## 2.1.0 - 2017-11-04
### Fixed
- Fixed little issues here and there (mostly JavaScript) that were not working right in EE 4
### New
- Added support for Fluid Field in EE 4

## 2.0.11 - 2017-09-19
### Fixed
- Fixed an issue where Drag and Drop uploads would not work in Firefox or IE ([#50](https://buzzingpixel.com/support/issue/50))
- Fixed an issue where Ansel would throw image tag errors if the original source image went missing ([#21](https://buzzingpixel.com/support/issue/21))

## 2.0.10 - 2017-09-11
### Fixed
- Fixed a styling issue in Channel Form
- Fixed an issue where Ansel would throw image tag errors if the original source image went missing ([#21](https://buzzingpixel.com/support/issue/21))
- Fixed an issue where a field with a requirement for Cover might not pass validation again after failing it (aggressive ExpressionEngine Javascript) ([#41](https://buzzingpixel.com/support/issue/41))
- Fixed CSS issues with Ansel in a Bloqs field
- Fixed an issue where files uploaded with spaces in the name would retain those spaces and general cause problems of a… problematic nature. ([#19](https://buzzingpixel.com/support/issue/19))

## 2.0.9 - 2017-08-18
### Fixed
- Fixed a Channel Form bug where Ansel’s CSS and JS might not be set if the field is called in some manner before channel form ([#37](https://buzzingpixel.com/support/issue/37))

## 2.0.7 - 2017-07-31
### Fixed
- Fixed an issue where the type of `grid` was hard coded, preventing the `grid_delete` field type method from being compatible with an upcoming version of Bloqs

## 2.0.6 - 2017-07-03
### Fixed
- Fixed an issue where Ansel's cache clean up routine may produce PHP errors in some environments

## 2.0.5 - 2017-06-24
### Changed
- Improved minimum image dimensions not met message with the required image dimensions
### Fixed
- Fixed a bug where using the EE/Assets/Treasury file choosers would bypass minimum image dimensions

## 2.0.4 - 2017-04-24
### Fixed
- Fixed a very rare issue where cache clean up routines might try to remove files that don't exist
- Fixed an issue where Low Variables that were not Ansel fields could not be saved because of "invalid form control not focusable" error
- Fixed a rare issue where a PHP warning might be thrown when saving a field from data sent to Ansel's methods from sources other than Ansel ([#14](https://buzzingpixel.com/support/issue/14))

## 2.0.3 - 2017-03-27
### Fixed
- Fixed an issue where Ansel’s generated thumbnails would be full manipulated image size
- Fixed a very minor and likely unnoticed issue where Ansel JavaScript might try to initialize on a field more than once

## 2.0.2 - 2017-03-19
### Fixed
- Fixed an issue where EE Directory manipulation variables were not being namespaced and so were unavailable in the tag pair ([#2](https://buzzingpixel.com/support/issue/2))
- Fix a minor lang grammar mistake
- Fixed an issue with the Ansel License Invalid notice where the purchase link would not go to the Ansel purchase page ([#4](https://buzzingpixel.com/support/issue/4))
- Fixed an issue where float values would not work properly in crop ratios ([#5](https://buzzingpixel.com/support/issue/5))
- Fix an issue when updating Ansel where browser cache may prevent CSS or JS changes from being picked up

## 2.0.1 - 2017-03-10
### Fixed
- Fixed an issue where Ansel field types in Bloqs fields would not initialize when adding a new Bloq

## 2.0.0 - 2017-03-08
### Changed
- BREAKING: Ansel 2.0.0 drops support for ExpressionEngine 2.x. If you need support for ExpressionEngine 2.x, you will need to use Ansel 1.x
- BREAKING: Dropped the `{img:tag}` variable in the tag pair. Use the `{img:url}` variable to build your own `<img>` tag
- BREAKING: EE directory manipulations are no longer included by default because of the additional query. In order to use them, include the parameter `manipulations="true"`
- Every line of code has been re-written in Ansel 2. While the original code was referenced or copied over, it was all evaluated for efficiency and maintainability
- A large majority of the codebase is now covered by PHPUnit testing. For those that are not as technically inclined, this simply means there’s now less of a chance of breaking things when making changes to the codebase
### Deprecated
- `{img:file_size}` is deprecated. Use `{img:filesize}`
- `{img:modified_date}` is deprecated. Use `{img:modify_date}`
### Added
- Ansel 2 features a drag and drop uploading interface for fields — images uploaded through this interface that do not meet requirements are rejected and never actually get uploaded to the file manager
- This drag and drop uploading interface also sports batch uploading
- Ansel is now 100% mobile friendly
- Rows and images are now 1 to 1. This creates a simpler user interface. You can no longer delete an image from the row, you can only adjust the crop. If you want a new image, upload or select it to create a new row.
- More images than the field settings allow can now be uploaded to a field. When you go over the field image limit, the image row will be grayed out to indicate that it will not be displayed
- Field options now include the ability to require the Title, Caption, or Cover fields
- Field options now include the ability to customize the label of the Title, Caption and Cover fields
- Any image tag parameter where it makes sense to do so now feature a `not_` prefix counterpart. For instance: `not_title="foo|baz"`
- Channel form support
- ImageMagick is now supported if present on the server
- `jpegoptim`, `gifsicle`, and `optipng` are now supported and utilized if installed on the server (each can be disabled individually via config file if desired, and there is also a config setting to force the use of GD even when ImageMagick is installed)
- New tag params:
    - `site_id="2"`
    - `original_location_type="ee|assets"`
    - `upload_location_type="assets|treasury"`
    - `filesize="182827"`
    - `filesize="> 182827"`
    - `filesize="< 182827"`
    - `original_filesize="182827"`
    - `original_filesize="< 182827"`
    - `original_filesize="> 182827"`
    - `width="300"`
    - `width="< 300"`
    - `width="> 300"`
    - `height="300"`
    - `height="< 300"`
    - `height="> 300"`
    - `position="2"`
    - `position="< 2"`
    - `position="> 2"`
    - `show_disabled="y" (shows images over limit)`
    - `order_by="date:desc|order:asc"`
    - `random="true"`
    - `manipulations="true" (EE manipulations are no longer included by default as it requires an additional query)`
    - `host="https://cdn.domain.com/"`
### Fixed
- I had a long conversation with `{img:path}` and all related path variables and they will now work all the time
- Fixed an issue with manipulating an image that could happen if max height was set but not max width

## 1.4.4 - 2017-02-06
### Fixed
- Fixed an issue where database return value typing might cause cover value to not return correctly

## 1.4.3 - 2017-01-16
### Fixed
- Fixed a minor incompatibility that would throw warnings in PHP 7.1

## 1.4.2 - 2016-12-30
### Fixed
- Fixed a code typo that would cause png image manipulations to fail when coming from an Assets source
- Fixed an issue where Grid and Blocks/Bloqs fields might not work on error post back
- Fixed an issue with un-editable images on error post back

## 1.4.1 - 2016-12-02
### Fixed
- Fixed an obscure bug with cropping files from an [Assets](https://eeharbor.com/assets) source where if the source was located and behind htaccess basic auth, retrieval and crop of the image on entry/field save would fail

## 1.4.0 - 2016-11-01
### Fixed
- Added support for [Assets 3](https://eeharbor.com/assets) from [EEHarbor](https://eeharbor.com/)
- No really, that’s it. There are no other bullet points for this release. But I can assure you that a lot of work and code went into this update to make sure Assets was well supported :simple_smile: — oh wait, this isn’t slack, that emoji code won’t work. Dang it. :facepalm:

## 1.3.5 - 2016-09-03
### Fixed
- Fixed a bug where directories from all sites would be available to field settings (MSM)
- Fixed a bug where `&hellip;` would be output directly in the directory chooser for field settings
- Fixed an issue where image tags could not get images from another MSM site

## 1.3.4 - 2016-08-11
### Fixed
- Fixed a bug where Ansel would not delete directories in the `_ansel_high_qual` and `_ansel_thumbs` directory when an image is deleted from an entry
- Fixed an issue where PNG transparency might not be maintained in some crop situations
- Fixed an issue where the crop area would show a black background instead of a transparent background for PNG transparent images

## 1.3.3 - 2016-07-13
### Fixed
- Fixed a very rare issue where Ansel would fail to get and cache a file if the server port is required in the URL
-  Fixed an issue where Ansel might not be able to cache remote files if there are spaces in the file name
-  Fixed a bug where the ee filepicker would not use the correct upload directory if more than one Ansel field is present on the publish page (EE 2)
-  Fixed a potential bug where a Resize operation might throw errors if the cache operation failed
- Fixed a bug where Ansel may not be able to get and cache a copy of a remote Treasury file to perform image manipulations on it

Please note that if you are using Ansel with Treasury, this version of Ansel requires the (also just released) Treasury 1.0.2.

## 1.3.2 - 2016-07-06
### Fixed
- Fixed a bug where the wrong cache path was set internally (EE 3)

## 1.3.1 - 2016-07-06
### Changed
- Optimized query efficiency when querying for multiple files from multiple rows
### Fixed
- Fixed a bug where Ansel fields could not be saved, added, or updated in EE 2
- Fixed a PHP error that would occur in PHP versions less than 5.6 when saving an Ansel field
- Fixed a PHP warning that would occur if there was an error parsing the update feed on the Ansel updates page in the Control Panel
- Fixed a bug where Ansel would try to delete directories it should not try to delete when deleting an Ansel image row from a field
- Fixed a bug where under certain circumstances, Ansel would fail to create the cache directories it needs
- Fixed a bug where EE might throw an error trying to delete a file not actually on disk (for realz this time, yo)
- Fixed an out of memory error that could occur when trying to increase the memory limit for working with large images (the opposite affect of what was intended)

PLEASE NOTE: Ansel used to try to compensate for limited memory situations in PHP settings (from a technical standpoint it was trying to do an ini_set call to increase the memory limit), but after consulting with some developers I trust, I feel doing this is a mistake and certainly was causing problems in as many cases as it was solving them. The bottom line is, I no longer feel it is appropriate to try to muck with the memory PHP has been assigned. That's a dev-ops decision and the memory available to PHP is set for a reason. If you run into memory errors saving large images (or any size image, really), you need to configure PHP to allow it more memory.

## 1.3.0 - 2016-07-01
### Added
- Added support for [Treasury file manager](https://buzzingpixel.com/software/treasury). Treasury is a new file manager by BuzingPixel that primarily provides Amazon S3, SFTP, and FTP support for ExpressionEngine 3.
- Added offset tag parameter to images tag
- Added new tag pair variables `{img:description_field}`, `{img:credit_field}`, `{img:location_field}`, `{img:original_description_field}`, `{img:original_credit_field}`, `{img:original_location_field}`
- Added time-based on-the-fly resize caching to keep from needing to check S3/FTP/SFTP or other remote files on every page load. By default, Ansel assumes the cached file is on the server forever. Number of seconds cached can be specified with the cache_time parameter on the `{img:tag}` or the `{img:url:resize}` tags.
### Changed
- Under-the-hood improvements and code optimizations
- Memory usage optimizations
- Ansel now handles no ending slashes on EE directory path settings better
- Detect missing source image and warn user when trying to edit a cropped file where source image is missing
### Fixed
- Fixed an issue where Ansel might interfere with PHP’s configured memory settings
- Fixed a bug where a PHP error might be thrown when using `{img:tag}` or `{img:url:resize}` on Bloqs fields (EE 3)
- Fixed an obscure bug where Blocks field settings were being cached internally as a Grid field settings, which under very rare circumstances could cause very strange problems (EE 2)
- Fixed a bug where deleting an Ansel row where the Ansel image had been deleted from the file manager would result in an EE error and the row could not be deleted
- Fixed some minor stylistic issues in EE 3
- Fixed an issue where Ansel’s version of Jcrop might conflict with the version of Jcrop used by other add-ons (such as Channel Images)
### Removed
- The `Image cache location` and `Image cache url` settings have been removed. Ansel now stores image manipulations in the save directory of the source image. This change was necessary to accommodate files in a non-local location and have on-the-fly manipulations also stored in the same location. You may want to remove the old `_ansel_image_cache` directory and the cached files in it.

## 1.2.0 - 2016-05-23
### Added
- Added an Updates page so you can see the latest updates to Ansel and whether or not you are up to date
### Fixed
- Fixed a deprecated call to `xss_clean()` function in EE 3
- Fixed an issue with field setting validation that would cause a fatal error in PHP 5.3
- Removed a stray `console.log` statement lurking in the Javascript

## 1.1.1 - 2016-04-25
### Added   
- Instructions on how to use the Upload/Save directory field settings will now be shown above those settings when creating/editing an Ansel field. You can disable this in Ansel settings or with the config override (`$config['ansel']['hide_source_save_instructions'] = true;`).
### Fixed
- Fixed a bug where the `{img:tag}` and `{img:url:resize}` tag variables could cause PHP warnings if no tag parameters provided
- Fixed a bug where images with spaces in the file names would not be properly URL encoded
- Fixed a bug in EE 2 where there was too much space between the label and the explanation on the Ansel settings page

## 1.1.0 - 2016-04-18
### Added
- Added support for Bloqs for EE 3 (http://www.qdigitalstudio.com/bloqs)

## 1.0.4 - 2016-04-15
### Fixed
- Fixed minor visual styling issues with an upcoming release of ExpressionEngine

## 1.0.3 - 2016-03-29
### Fixed
- Fixed a bug that would cause new Grid or Blocks fields to be populated with any other images already present in the field on entry load (facepalm.gif)
- Fixed a bug with `{img:tag}` and `{img:url}` where end of tag would prematurely match if another EE tag was used inside of one of the tag parameters

## 1.0.2 - 2016-03-22
### Fixed
- Fixed a bug where Ansel was not compatible with PHP 5.3

How did this happen? Well, you see...

![Speechless](https://buzzingpixel.com/uploads-static/general/mal-speechless.gif)

## 1.0.1 - 2016-03-18
### Fixed
- Fixed a bug where clicking anywhere after cropping an image resulted in angry console error
- Fixed a bug where modal dialogs (such as for an image too small for the field settings) were shy and would not display (the modal dialog is seeing a counselor about this issue) (EE 3)
- Fixed a bug where PNG images with transparency would become decidedly less transparent
- Fixed an image resize calculation bug that could happen under the right conditions
- Fixed a bug where the browser may load the image for cropping from cache if you had uploaded a new image with the exact same file path

## 1.0.0 - 2016-03-08
This is the initial release of Ansel for ExpressionEngine.

Ansel lets you create beautiful images, the right size every time in a way that’s friendly and easy for users of all types.
