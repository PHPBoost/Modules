<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      LamPDL
 * @version     PHPBoost 6.0 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2026 03 01
 */

####################################################
#                    English                       #
####################################################

$lang['devtools.module.title'] = 'PBT Manager';

// Tabs
$lang['devtools.tab.modules']  = 'Installed modules';
$lang['devtools.tab.themes']   = 'Themes';
$lang['devtools.tab.config']   = 'Settings';

// Local status table
$lang['devtools.local.modules']        = 'Installed modules';
$lang['devtools.col.name']             = 'Name';
$lang['devtools.col.version']          = 'Installed version';
$lang['devtools.col.status']           = 'Status';
$lang['devtools.col.remote.version']   = 'Available version';
$lang['devtools.col.actions']          = 'Actions';

$lang['devtools.status.active']        = 'Active';
$lang['devtools.status.inactive']      = 'Inactive';
$lang['devtools.status.not.installed'] = 'Not installed';
$lang['devtools.status.up.to.date']    = 'Up to date';
$lang['devtools.status.update.avail']  = 'Update available';
$lang['devtools.status.unknown']       = 'Unknown';

// Actions
$lang['devtools.action.refresh']          = 'Refresh';
$lang['devtools.action.close']            = 'Close';
$lang['devtools.action.activate']         = 'Activate';
$lang['devtools.action.activate.title']   = 'This module will be available again';
$lang['devtools.action.deactivate']       = 'Deactivate';
$lang['devtools.action.deactivate.title'] = 'This module will be unavailable without data loss';
$lang['devtools.action.uninstall']        = 'Uninstall';
$lang['devtools.action.uninstall.soft']   = 'Uninstall';
$lang['devtools.action.uninstall.hard']   = 'Uninstall and delete';
$lang['devtools.action.local.install']    = 'Install';
$lang['devtools.action.install.sel']      = 'Install selection';
$lang['devtools.action.select.all']       = 'Select all';
$lang['devtools.action.deselect.all']     = 'Deselect all';

$lang['devtools.uninstall.soft.title']   = 'This module can be reinstalled (files are kept)';
$lang['devtools.uninstall.hard.title']   = 'This module will no longer be available without downloading it again';
$lang['devtools.uninstall.confirm']      = 'Confirm uninstallation of this module?';
$lang['devtools.uninstall.soft.confirm'] = 'Uninstall this module? Files will be kept and it can be reinstalled.';
$lang['devtools.uninstall.hard.confirm'] = 'Permanently delete this module? Files will be removed and it will need to be downloaded again.';
$lang['devtools.uninstall.drop.confirm'] = 'Also delete files from the /modules folder?';

// Repo panel
$lang['devtools.repo.add']          = 'Add repository';
$lang['devtools.repo.add.confirm']  = 'Add';
$lang['devtools.repo.cancel']       = 'Cancel';
$lang['devtools.repo.select.error'] = 'Please select a repository.';
$lang['devtools.repo.org']          = 'GitHub organisation';
$lang['devtools.repo.pick']         = 'Repository';
$lang['devtools.repo.path']         = 'Sub-folder';
$lang['devtools.repo.label']        = 'Display label';

// Remote repo panel
$lang['devtools.remote.title']     = 'Remote repositories';
$lang['devtools.remote.repo']      = 'Repository';
$lang['devtools.remote.branch']    = 'Branch';
$lang['devtools.remote.available'] = 'Available modules';
$lang['devtools.remote.loading']   = 'Loading…';
$lang['devtools.remote.error']     = 'Error loading remote repository.';
$lang['devtools.remote.none']      = 'No modules found in this branch.';

// Install feedback
$lang['devtools.install.success']      = 'Module(s) installed successfully.';
$lang['devtools.install.error']        = 'Installation error: ';
$lang['devtools.install.no.selection'] = 'No module selected.';

// Restore tab
$lang['devtools.restore.title']    = 'Restore';
$lang['devtools.restore.none']     = 'No backup available.';
$lang['devtools.restore.date']     = 'Backup date';
$lang['devtools.restore.size']     = 'Size';
$lang['devtools.restore.download'] = 'Download .sql';

// Config
$lang['devtools.config.repos']        = 'GitHub repositories';
$lang['devtools.config.repo.add']     = 'Add repository';
$lang['devtools.config.repo.delete']  = 'Delete';
$lang['devtools.config.repo.org']     = 'GitHub organisation';
$lang['devtools.config.repo.pick']    = 'Repository';
$lang['devtools.config.repo.owner']   = 'Owner (e.g. LamPDL)';
$lang['devtools.config.repo.name']    = 'Repository name (e.g. LamPDL)';
$lang['devtools.config.repo.path']    = 'Module sub-folder (leave empty if root)';
$lang['devtools.config.repo.label']   = 'Display label';
$lang['devtools.config.github.token'] = 'GitHub token (optional, to avoid rate limits)';

// SEO
$lang['devtools.seo.description'] = 'PHPBoost module management on ' . GeneralConfig::load()->get_site_name() . '.';

// Import BDD tab
$lang['devtools.importbdd.title']      = 'Import DB';
$lang['devtools.importbdd.none']       = 'No module with SQL files found in /backup/importBDD/.';
$lang['devtools.importbdd.error']      = 'Loading error.';
$lang['devtools.importbdd.col.module'] = 'Module';
$lang['devtools.importbdd.col.files']  = 'Available SQL tables';
$lang['devtools.importbdd.col.date']   = 'Date';
$lang['devtools.importbdd.col.tables'] = 'Module tables';
$lang['devtools.importbdd.action']     = 'Import';
$lang['devtools.importbdd.confirm']    = 'Import tables for module "%s"? Existing tables will be dropped and recreated (DROP + CREATE + INSERT).';
$lang['devtools.importbdd.success']    = 'Import completed successfully.';
$lang['devtools.importbdd.importing']  = 'Importing…';

// File Review tab
$lang['devtools.review.title']                      = 'File review';
$lang['devtools.review.refresh']                    = 'Analyse';
$lang['devtools.review.refreshing']                 = 'Analysing…';
$lang['devtools.review.refresh.success']            = 'Analysis complete.';
$lang['devtools.review.clear']                      = 'Clear table';
$lang['devtools.review.clearing']                   = 'Clearing…';
$lang['devtools.review.clear.success']              = 'Table cleared.';
$lang['devtools.review.info']                       = 'The analysis scans the content of all compatible modules and cross-references files present on the server with those referenced in the database.';
$lang['devtools.review.incompatible']               = 'Incompatible module';
$lang['devtools.review.col.file']                   = 'File';
$lang['devtools.review.col.module']                 = 'Source module';
$lang['devtools.review.col.item']                   = 'Document';
$lang['devtools.review.col.edit']                   = 'Edit';
$lang['devtools.review.col.context']                = 'Context';
$lang['devtools.review.col.user']                   = 'Uploaded by';
$lang['devtools.review.col.date']                   = 'Upload date';
$lang['devtools.review.col.size']                   = 'Size';
$lang['devtools.review.section.files.on.server']    = 'Files on server (/upload)';
$lang['devtools.review.group.upload']               = 'Upload';
$lang['devtools.review.group.errors']               = 'Errors';
$lang['devtools.review.group.gallery']              = 'Gallery';
$lang['devtools.review.section.files.in.upload']    = 'Files in upload table';
$lang['devtools.review.section.files.in.content']   = 'Files used in content';
$lang['devtools.review.section.all.unused']         = 'All unused files';
$lang['devtools.review.section.used.not.on.server'] = 'Used but missing from server (404 error)';
$lang['devtools.review.section.unused.with.users']  = 'Unused files (with upload record)';
$lang['devtools.review.section.orphan']             = 'Orphan files (no upload record)';
$lang['devtools.review.section.gallery.folder']     = 'Files in /gallery/pics';
$lang['devtools.review.section.gallery.table']      = 'Files in gallery table';
$lang['devtools.review.section.no.gallery.folder']  = 'In gallery table but missing from folder';
$lang['devtools.review.section.no.gallery.table']   = 'In folder but missing from gallery table';
$lang['devtools.review.group.gallery.errors']       = 'Gallery anomalies';
$lang['devtools.review.total.errors']               = 'Total anomalies';

// Tooltips
$lang['devtools.review.tip.onserver']       = 'Files physically present in the /upload folder on the server.';
$lang['devtools.review.tip.inupload']       = 'Files referenced in the upload table of the database.';
$lang['devtools.review.tip.incontent']      = 'Files whose path appears in module content (articles, news, wiki…). Deduplicated.';
$lang['devtools.review.tip.allunused']      = 'Files present in the upload table but whose path does not appear in any content.';
$lang['devtools.review.tip.usednoserver']   = 'Files referenced in content but not found on the server — they generate 404 errors.';
$lang['devtools.review.tip.unuseduser']     = 'Files not used in any content but linked to a user via the upload table.';
$lang['devtools.review.tip.orphan']         = 'Files physically present in /upload on the server but with no entry in the upload table.';
$lang['devtools.review.tip.galleryfolder']  = 'Files physically present in the /gallery/pics folder on the server.';
$lang['devtools.review.tip.gallerytable']   = 'Files referenced in the Gallery module table.';
$lang['devtools.review.tip.nogalleryfolder']= 'Files present in the Gallery table but not found in the /gallery/pics folder.';
$lang['devtools.review.tip.nogallerytable'] = 'Files present in the /gallery/pics folder but with no entry in the Gallery table.';

// Lang Review tab
$lang['devtools.langrev.title']                = 'Lang Review';
$lang['devtools.langrev.select.module']        = 'Select a module';
$lang['devtools.langrev.analyzing']            = 'Analyzing…';
$lang['devtools.langrev.total.keys']           = 'keys total';
$lang['devtools.langrev.section.unused']       = 'Unused variables';
$lang['devtools.langrev.section.dup.internal'] = 'Internal duplicates (same value in same module)';
$lang['devtools.langrev.section.dup.external'] = 'Cross-module duplicates (same value in other modules)';
$lang['devtools.langrev.col.key']              = 'Variable name';
$lang['devtools.langrev.col.lang']             = 'Version';
$lang['devtools.langrev.col.value.fr']         = 'FR Value';
$lang['devtools.langrev.col.value.en']         = 'EN Value';
$lang['devtools.langrev.col.value']            = 'Value';
$lang['devtools.langrev.col.keys']             = 'Affected variables';
$lang['devtools.langrev.col.matches']          = 'Matches';
$lang['devtools.langrev.none']                 = 'No results';
$lang['devtools.langrev.error']                = 'Analysis error';
?>
