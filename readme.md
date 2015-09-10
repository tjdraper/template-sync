# Template Sync 1.0.0-b.3 for ExpressionEngine

Make your file system (and version control!) the master of your templates in all ways.

ExpressionEngine has basic capability for files as templates, but if you delete a template file from the file system it is not deleted from EE, or if you change the file extension, it does not change the template type in EE. This extension fixes both of those issues.

## How it works

Template sync runs any time you visit a control panel page. If a template is in the database but not the file system, it will be deleted. If a template group is in the database but not the file system, it will be deleted. If a file extension has changed, the template type will be updated.

Additionally, since All ExpressionEngine template groups have an index template by default, if an index file does not exist, one is created with `{redirect="404"}` as the content. The content of this template can be changed at any time.

Template Sync will also check to see if you have defined an `ENV` constant. If that constant is defined and not set to `prod`, template sync will go ahead and run. That means in your local and staging environments, it will always keep your templates fully in sync. And when you roll out changes to production, simply visiting the control panel will sync your template changes.