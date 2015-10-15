# Template Sync 1.0.0-b.4 for ExpressionEngine

Make your file system (and version control!) the master of your templates in all ways.

ExpressionEngine has basic capability for files as templates, but if you delete a template file from the file system it is not deleted from EE, or if you change the file extension, it does not change the template type in EE. This extension fixes both of those issues.

## Compatibility

Template Sync is compatible with both ExpressionEngine 2 (testing with 2.10.1) and ExpressionEngine 3.0.0. However it has not been tested in a production environment with EE 3 so you should test thoroughly and MAKE SURE YOU HAVE DATABASE BACKUPS!

## Installing

EE2:

- Move the `system/expressionengine/third_party/template_sync` directory to your ExpressionEngine third party directory.
- Go to Add-Ons > Extensions and install Template Sync

EE3:

- Move the `system/expressionengine/third_party/template_sync` directory to `system/user/addons`
- Go to Add-on Manager, locate Template Sync and click install

## How it works

Template sync runs any time you visit a control panel page. If a template is in the database but not the file system, it will be deleted. If a template group is in the database but not the file system, it will be deleted. If a file extension has changed, the template type will be updated.

Additionally, since All ExpressionEngine template groups have an index template by default, if an index file does not exist, one is created with `{redirect="404"}` as the content. The content of this template can be changed at any time.

Template Sync will also check to see if you have defined an `ENV` constant. If that constant is defined and not set to `prod`, template sync will go ahead and run. That means in your local and staging environments, it will always keep your templates fully in sync. And when you roll out changes to production, simply visiting the control panel will sync your template changes.