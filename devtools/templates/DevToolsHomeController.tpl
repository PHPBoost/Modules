# INCLUDE MESSAGE_HELPER #

<div class="pbtm-container">

    <!-- ===================== ONGLETS ===================== -->
    <div class="pbtm-tabs">
        <button class="pbtm-tab active" data-tab="local">{@devtools.tab.modules}</button>
        <button class="pbtm-tab" data-tab="remote">{@devtools.remote.title}</button>
        <button class="pbtm-tab" data-tab="restore">{@devtools.restore.title}</button>
        <button class="pbtm-tab" data-tab="importbdd">{@devtools.importbdd.title}</button>
        <button class="pbtm-tab" data-tab="review">{@devtools.review.title}</button>
        <button class="pbtm-tab" data-tab="lang">{@devtools.langrev.title}</button>
        # IF C_IS_ADMIN #
        <a class="pbtm-tab" href="{U_CONFIG}">{@devtools.tab.config}</a>
        # ENDIF #
    </div>

    <!-- ===================== TAB 1: INSTALLED MODULES ===================== -->
    <section class="pbtm-section pbtm-tab-content active" id="pbtm-tab-local">
        <div class="pbtm-toolbar">
            <button type="button" class="pbtm-btn pbtm-btn-secondary" id="pbtm-refresh-local">&#x21bb; {@devtools.action.refresh}</button>
        </div>
        <div class="pbtm-table-wrapper">
            <table class="pbtm-table" id="pbtm-local-table">
                <thead>
                    <tr>
                        <th>{@devtools.col.name}</th>
                        <th>{@devtools.col.version}</th>
                        <th>{@devtools.col.status}</th>
                        <th>{@devtools.col.remote.version}</th>
                        <th>{@devtools.col.actions}</th>
                    </tr>
                </thead>
                <tbody>{MODULE_ROWS}</tbody>
            </table>
        </div>
    </section>

    <!-- ===================== TAB 2: REMOTE REPOSITORIES ===================== -->
    <section class="pbtm-section pbtm-tab-content" id="pbtm-tab-remote">

        <div class="pbtm-add-repo-panel" id="pbtm-add-repo-panel" style="display:none;">
            <strong>{@devtools.repo.add}</strong>
            <div class="pbtm-remote-controls" style="margin-top:.75rem;">
                <div class="pbtm-control-group">
                    <label>{@devtools.repo.org}</label>
                    <div style="display:flex;gap:.5rem;">
                        <input type="text" id="pbtm-add-org" value="PHPBoost" style="flex:1;padding:.35rem .6rem;border:1px solid #d1d5db;border-radius:4px;font-size:14px;">
                        <button type="button" class="pbtm-btn pbtm-btn-secondary" id="pbtm-load-org-repos">&#x21bb;</button>
                    </div>
                </div>
                <div class="pbtm-control-group">
                    <label>{@devtools.repo.pick}</label>
                    <select id="pbtm-add-repo-select" style="width:100%;">
                        <option value="">—</option>
                    </select>
                </div>
                <div class="pbtm-control-group">
                    <label>{@devtools.repo.path}</label>
                    <input type="text" id="pbtm-add-path" placeholder="modules" style="width:100%;padding:.35rem .6rem;border:1px solid #d1d5db;border-radius:4px;font-size:14px;box-sizing:border-box;">
                </div>
                <div class="pbtm-control-group">
                    <label>{@devtools.repo.label}</label>
                    <input type="text" id="pbtm-add-label" style="width:100%;padding:.35rem .6rem;border:1px solid #d1d5db;border-radius:4px;font-size:14px;box-sizing:border-box;">
                </div>
                <div class="pbtm-control-group" style="justify-content:flex-end;">
                    <label>&nbsp;</label>
                    <button type="button" class="pbtm-btn pbtm-btn-ok" id="pbtm-confirm-add-repo">{@devtools.repo.add.confirm}</button>
                    <button type="button" class="pbtm-btn pbtm-btn-secondary" id="pbtm-cancel-add-repo" style="margin-top:.25rem;">{@devtools.repo.cancel}</button>
                </div>
            </div>
            <p id="pbtm-add-repo-error" style="display:none;color:#dc2626;font-size:13px;margin-top:.5rem;"></p>
        </div>

        <div class="pbtm-remote-controls">
            <div class="pbtm-control-group">
                <label for="pbtm-repo-select">{@devtools.remote.repo}</label>
                <select id="pbtm-repo-select">{REPO_OPTIONS}</select>
            </div>
            <div class="pbtm-control-group">
                <label for="pbtm-branch-select">{@devtools.remote.branch}</label>
                <select id="pbtm-branch-select" disabled>
                    <option value="">{@devtools.remote.loading}</option>
                </select>
            </div>
            <div class="pbtm-control-group pbtm-control-refresh">
                <label>&nbsp;</label>
                <button type="button" class="pbtm-btn pbtm-btn-secondary" id="pbtm-refresh-remote">&#x21bb; {@devtools.action.refresh}</button>
                <button type="button" class="pbtm-btn pbtm-btn-ok" id="pbtm-show-add-repo" style="margin-top:.25rem;">+ {@devtools.repo.add}</button>
            </div>
        </div>

        <div id="pbtm-remote-feedback" class="pbtm-feedback" style="display:none;"></div>

        <div id="pbtm-remote-modules" style="display:none;">
            <div class="pbtm-bulk-actions">
                <button type="button" class="pbtm-btn pbtm-btn-secondary" id="pbtm-select-all">{@devtools.action.select.all}</button>
                <button type="button" class="pbtm-btn pbtm-btn-secondary" id="pbtm-deselect-all">{@devtools.action.deselect.all}</button>
                <button type="button" class="pbtm-btn pbtm-btn-ok" id="pbtm-install-sel">{@devtools.action.install.sel}</button>
            </div>
            <div class="pbtm-table-wrapper">
                <table class="pbtm-table" id="pbtm-remote-table">
                    <thead>
                        <tr>
                            <th class="pbtm-col-check"><input type="checkbox" id="pbtm-check-all" title="{@devtools.action.select.all}"></th>
                            <th>{@devtools.col.name}</th>
                            <th>{@devtools.col.remote.version}</th>
                            <th>{@devtools.col.version}</th>
                            <th>{@devtools.col.status}</th>
                        </tr>
                    </thead>
                    <tbody id="pbtm-remote-tbody"></tbody>
                </table>
            </div>
        </div>

        <p id="pbtm-remote-none" style="display:none;" class="pbtm-info">{@devtools.remote.none}</p>
        <div id="pbtm-remote-loading" class="pbtm-loading-row">
            <span class="pbtm-spinner"></span>{@devtools.remote.loading}
        </div>
        <p id="pbtm-remote-error" style="display:none;" class="pbtm-error">{@devtools.remote.error}</p>
    </section>

    <!-- ===================== ONGLET 3 : RESTAURATION ===================== -->
    <section class="pbtm-section pbtm-tab-content" id="pbtm-tab-restore">
        <div class="pbtm-toolbar">
            <button type="button" class="pbtm-btn pbtm-btn-secondary" id="pbtm-refresh-restore">&#x21bb; {@devtools.action.refresh}</button>
        </div>
        <div id="pbtm-restore-loading" class="pbtm-loading-row" style="display:none;">
            <span class="pbtm-spinner"></span>{@devtools.remote.loading}
        </div>
        <p id="pbtm-restore-none" style="display:none;" class="pbtm-info">{@devtools.restore.none}</p>
        <p id="pbtm-restore-error" style="display:none;" class="pbtm-error">{@devtools.remote.error}</p>
        <div id="pbtm-restore-feedback" class="pbtm-feedback" style="display:none;"></div>
        <div class="pbtm-table-wrapper" id="pbtm-restore-wrapper" style="display:none;">
            <table class="pbtm-table">
                <thead>
                    <tr>
                        <th>{@devtools.col.name}</th>
                        <th>{@devtools.restore.date}</th>
                        <th>{@devtools.restore.size}</th>
                        <th>{@devtools.col.status}</th>
                        <th>{@devtools.col.actions}</th>
                    </tr>
                </thead>
                <tbody id="pbtm-restore-tbody"></tbody>
            </table>
        </div>
    </section>

    <!-- ===================== ONGLET 4 : IMPORT BDD ===================== -->
    <section class="pbtm-section pbtm-tab-content" id="pbtm-tab-importbdd">
        <div class="pbtm-info-block">
            <p>&#x2139;&#xFE0F; Cet outil permet d'importer des tables dans un module existant ou de le compléter avec de nouvelles données.</p>
            <p>&#x26A0;&#xFE0F; Si la table cible existe déjà, elle sera <strong>supprimée avant import</strong> des nouvelles données (DROP TABLE + CREATE + INSERT).</p>
        </div>
        <div class="pbtm-toolbar">
            <button type="button" class="pbtm-btn pbtm-btn-secondary" id="pbtm-refresh-importbdd">&#x21bb; {@devtools.action.refresh}</button>
        </div>
        <div id="pbtm-importbdd-loading" class="pbtm-loading-row" style="display:none;">
            <span class="pbtm-spinner"></span>{@devtools.remote.loading}
        </div>
        <p id="pbtm-importbdd-none"  style="display:none;" class="pbtm-info">{@devtools.importbdd.none}</p>
        <p id="pbtm-importbdd-error" style="display:none;" class="pbtm-error">{@devtools.importbdd.error}</p>
        <div id="pbtm-importbdd-feedback" class="pbtm-feedback" style="display:none;"></div>
        <div class="pbtm-table-wrapper" id="pbtm-importbdd-wrapper" style="display:none;">
            <table class="pbtm-table">
                <thead>
                    <tr>
                        <th>{@devtools.importbdd.col.module}</th>
                        <th>{@devtools.importbdd.col.tables}</th>
                        <th>{@devtools.importbdd.col.files}</th>
                        <th>{@devtools.importbdd.col.date}</th>
                        <th>{@devtools.col.actions}</th>
                    </tr>
                </thead>
                <tbody id="pbtm-importbdd-tbody"></tbody>
            </table>
        </div>
    </section>

    <!-- JS labels stored in data-attributes to avoid escaping issues -->
    <div id="pbtm-lang" style="display:none;"
        data-loading="{@devtools.remote.loading}"
        data-error="{@devtools.remote.error}"
        data-none="{@devtools.remote.none}"
        data-success="{@devtools.install.success}"
        data-err-prefix="{@devtools.install.error}"
        data-no-sel="{@devtools.install.no.selection}"
        data-active="{@devtools.status.active}"
        data-inactive="{@devtools.status.inactive}"
        data-not-inst="{@devtools.status.not.installed}"
        data-up-to-date="{@devtools.status.up.to.date}"
        data-update-avail="{@devtools.status.update.avail}"
        data-unknown="{@devtools.status.unknown}"
        data-activate="{@devtools.action.activate}"
        data-deactivate="{@devtools.action.deactivate}"
        data-uninstall="{@devtools.action.uninstall}"
        data-local-install="{@devtools.action.local.install}"
        data-confirm="{@devtools.uninstall.confirm}"
        data-confirm-soft="{@devtools.uninstall.soft.confirm}"
        data-confirm-hard="{@devtools.uninstall.hard.confirm}"
        data-restore-download="{@devtools.restore.download}"
        data-select-repo="{@devtools.repo.select.error}"
        data-importbdd-confirm="{@devtools.importbdd.confirm}"
        data-importbdd-success="{@devtools.importbdd.success}"
        data-importbdd-importing="{@devtools.importbdd.importing}"
        data-importbdd-action="{@devtools.importbdd.action}"
    ></div>
    <!-- ===================== ONGLET 5 : REVUE DE FICHIERS ===================== -->
    <section class="pbtm-section pbtm-tab-content" id="pbtm-tab-review">
        <div class="pbtm-toolbar">
            <button type="button" class="pbtm-btn pbtm-btn-ok" id="pbtm-review-refresh">{@devtools.review.refresh}</button>
            <button type="button" class="pbtm-btn pbtm-btn-danger" id="pbtm-review-clear">{@devtools.review.clear}</button>
        </div>
        <div id="pbtm-review-loading" class="pbtm-loading-row" style="display:none;">
            <span class="pbtm-spinner"></span>{@devtools.review.refreshing}
        </div>
        <div id="pbtm-review-feedback" class="pbtm-feedback" style="display:none;"></div>

        <div id="pbtm-review-counters" style="display:none;">
            <div class="pbtm-rv-dashboard">

                <!-- Colonne gauche : situation des fichiers -->
                <div class="pbtm-rv-col">
                    <div class="pbtm-rv-group">
                        <div class="pbtm-rv-group-title">{@devtools.review.group.upload}</div>
                        <button class="pbtm-rv-row pbtm-review-section-btn" data-section="onserver">
                            <span class="pbtm-rv-label">{@devtools.review.section.files.on.server}</span>
                            <span class="pbtm-rv-row-right">
                                <span class="pbtm-rv-tip" data-tip="{@devtools.review.tip.onserver}">&#128065;</span>
                                <span class="pbtm-rv-count" id="rv-cnt-onserver">—</span>
                            </span>
                        </button>
                        <button class="pbtm-rv-row pbtm-review-section-btn" data-section="inupload">
                            <span class="pbtm-rv-label">{@devtools.review.section.files.in.upload}</span>
                            <span class="pbtm-rv-row-right">
                                <span class="pbtm-rv-tip" data-tip="{@devtools.review.tip.inupload}">&#128065;</span>
                                <span class="pbtm-rv-count" id="rv-cnt-inupload">—</span>
                            </span>
                        </button>
                        <button class="pbtm-rv-row pbtm-review-section-btn" data-section="incontent">
                            <span class="pbtm-rv-label">{@devtools.review.section.files.in.content}</span>
                            <span class="pbtm-rv-row-right">
                                <span class="pbtm-rv-tip" data-tip="{@devtools.review.tip.incontent}">&#128065;</span>
                                <span class="pbtm-rv-count" id="rv-cnt-incontent">—</span>
                            </span>
                        </button>
                        <button class="pbtm-rv-row pbtm-review-section-btn" data-section="allunused">
                            <span class="pbtm-rv-label">{@devtools.review.section.all.unused}</span>
                            <span class="pbtm-rv-row-right">
                                <span class="pbtm-rv-tip" data-tip="{@devtools.review.tip.allunused}">&#128065;</span>
                                <span class="pbtm-rv-count pbtm-rv-count-warn" id="rv-cnt-allunused">—</span>
                            </span>
                        </button>
                    </div>
                    <div class="pbtm-rv-group">
                        <div class="pbtm-rv-group-title">{@devtools.review.group.gallery}</div>
                        <button class="pbtm-rv-row pbtm-review-section-btn" data-section="ingalleryfolder">
                            <span class="pbtm-rv-label">{@devtools.review.section.gallery.folder}</span>
                            <span class="pbtm-rv-row-right">
                                <span class="pbtm-rv-tip" data-tip="{@devtools.review.tip.galleryfolder}">&#128065;</span>
                                <span class="pbtm-rv-count" id="rv-cnt-galleryfolder">—</span>
                            </span>
                        </button>
                        <button class="pbtm-rv-row pbtm-review-section-btn" data-section="ingallerytable">
                            <span class="pbtm-rv-label">{@devtools.review.section.gallery.table}</span>
                            <span class="pbtm-rv-row-right">
                                <span class="pbtm-rv-tip" data-tip="{@devtools.review.tip.gallerytable}">&#128065;</span>
                                <span class="pbtm-rv-count" id="rv-cnt-gallerytable">—</span>
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Colonne droite : anomalies -->
                <div class="pbtm-rv-col">
                    <div class="pbtm-rv-group">
                        <div class="pbtm-rv-group-title">{@devtools.review.group.errors}</div>
                        <button class="pbtm-rv-row pbtm-review-section-btn" data-section="usednoserver">
                            <span class="pbtm-rv-label">{@devtools.review.section.used.not.on.server}</span>
                            <span class="pbtm-rv-row-right">
                                <span class="pbtm-rv-tip" data-tip="{@devtools.review.tip.usednoserver}">&#128065;</span>
                                <span class="pbtm-rv-count pbtm-rv-count-error" id="rv-cnt-usednoserver">—</span>
                            </span>
                        </button>
                        <button class="pbtm-rv-row pbtm-review-section-btn" data-section="unuseduser">
                            <span class="pbtm-rv-label">{@devtools.review.section.unused.with.users}</span>
                            <span class="pbtm-rv-row-right">
                                <span class="pbtm-rv-tip" data-tip="{@devtools.review.tip.unuseduser}">&#128065;</span>
                                <span class="pbtm-rv-count pbtm-rv-count-error" id="rv-cnt-unuseduser">—</span>
                            </span>
                        </button>
                        <button class="pbtm-rv-row pbtm-review-section-btn" data-section="orphan">
                            <span class="pbtm-rv-label">{@devtools.review.section.orphan}</span>
                            <span class="pbtm-rv-row-right">
                                <span class="pbtm-rv-tip" data-tip="{@devtools.review.tip.orphan}">&#128065;</span>
                                <span class="pbtm-rv-count pbtm-rv-count-error" id="rv-cnt-orphan">—</span>
                            </span>
                        </button>
                        <div class="pbtm-rv-row pbtm-rv-total">
                            <span class="pbtm-rv-label">{@devtools.review.total.errors}</span>
                            <span class="pbtm-rv-count pbtm-rv-count-error" id="rv-cnt-total-errors">—</span>
                        </div>
                    </div>
                    <div class="pbtm-rv-group">
                        <div class="pbtm-rv-group-title">{@devtools.review.group.gallery.errors}</div>
                        <button class="pbtm-rv-row pbtm-review-section-btn" data-section="nogalleryfolder">
                            <span class="pbtm-rv-label">{@devtools.review.section.no.gallery.folder}</span>
                            <span class="pbtm-rv-row-right">
                                <span class="pbtm-rv-tip" data-tip="{@devtools.review.tip.nogalleryfolder}">&#128065;</span>
                                <span class="pbtm-rv-count pbtm-rv-count-error" id="rv-cnt-nogalleryfolder">—</span>
                            </span>
                        </button>
                        <button class="pbtm-rv-row pbtm-review-section-btn" data-section="nogallerytable">
                            <span class="pbtm-rv-label">{@devtools.review.section.no.gallery.table}</span>
                            <span class="pbtm-rv-row-right">
                                <span class="pbtm-rv-tip" data-tip="{@devtools.review.tip.nogallerytable}">&#128065;</span>
                                <span class="pbtm-rv-count pbtm-rv-count-error" id="rv-cnt-nogallerytable">—</span>
                            </span>
                        </button>
                    </div>
                </div>

            </div><!-- /.pbtm-rv-dashboard -->

            <!-- Panneau de détail unique -->
            <div class="pbtm-rv-detail" id="pbtm-rv-detail" style="display:none;">
                <div class="pbtm-rv-detail-header">
                    <strong class="pbtm-rv-detail-title"></strong>
                    <button type="button" class="pbtm-btn pbtm-btn-secondary pbtm-rv-detail-close">&times;</button>
                </div>
                <div class="pbtm-table-wrapper">
                    <table class="pbtm-table">
                        <thead class="pbtm-rv-thead"></thead>
                        <tbody class="pbtm-rv-tbody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================== TAB 6: LANG REVIEW ===================== -->
    <section class="pbtm-section pbtm-tab-content" id="pbtm-tab-lang">
        <div id="pbtm-lang-dashboard">
            <!-- Populated by JS after scanning all modules -->
        </div>

        <div id="pbtm-lang-body">
            <!-- Populated by JS -->
        </div>
    </section>

    <!-- Lang tab: URL + token passés en inputs cachés pour le bloc JS standalone -->
    <input type="hidden" id="pbtm-lang-url"  value="{URL_AJAX_LANG}">
    <input type="hidden" id="pbtm-lang-csrf" value="{CSRF_TOKEN}">

    <!-- JS labels for Lang tab -->
    <div id="pbtm-lang-labels" style="display:none;"
        data-select="{@devtools.langrev.select.module}"
        data-analyzing="{@devtools.langrev.analyzing}"
        data-total-keys="{@devtools.langrev.total.keys}"
        data-section-unused="{@devtools.langrev.section.unused}"
        data-section-dup-internal="{@devtools.langrev.section.dup.internal}"
        data-section-dup-external="{@devtools.langrev.section.dup.external}"
        data-col-key="{@devtools.langrev.col.key}"
        data-col-lang="{@devtools.langrev.col.lang}"
        data-col-value-fr="{@devtools.langrev.col.value.fr}"
        data-col-value-en="{@devtools.langrev.col.value.en}"
        data-col-value="{@devtools.langrev.col.value}"
        data-col-keys="{@devtools.langrev.col.keys}"
        data-col-matches="{@devtools.langrev.col.matches}"
        data-none="{@devtools.langrev.none}"
        data-error="{@devtools.langrev.error}"
    ></div>

    <!-- JS labels for Review tab -->
    <div id="pbtm-review-lang" style="display:none;"
        data-refresh="{@devtools.review.refresh}"
        data-refreshing="{@devtools.review.refreshing}"
        data-refresh-success="{@devtools.review.refresh.success}"
        data-clear-success="{@devtools.review.clear.success}"
        data-error="{@devtools.remote.error}"
        data-incompatible="{@devtools.review.incompatible}"
        data-section-onserver="{@devtools.review.section.files.on.server}"
        data-section-inupload="{@devtools.review.section.files.in.upload}"
        data-section-incontent="{@devtools.review.section.files.in.content}"
        data-section-allunused="{@devtools.review.section.all.unused}"
        data-section-usednoserver="{@devtools.review.section.used.not.on.server}"
        data-section-unuseduser="{@devtools.review.section.unused.with.users}"
        data-section-orphan="{@devtools.review.section.orphan}"
        data-section-ingalleryfolder="{@devtools.review.section.gallery.folder}"
        data-section-ingallerytable="{@devtools.review.section.gallery.table}"
        data-section-nogalleryfolder="{@devtools.review.section.no.gallery.folder}"
        data-section-nogallerytable="{@devtools.review.section.no.gallery.table}"
        data-col-file="{@devtools.review.col.file}"
        data-col-module="{@devtools.review.col.module}"
        data-col-item="{@devtools.review.col.item}"
        data-col-edit="{@devtools.review.col.edit}"
        data-col-context="{@devtools.review.col.context}"
        data-col-user="{@devtools.review.col.user}"
        data-col-date="{@devtools.review.col.date}"
        data-col-size="{@devtools.review.col.size}"
    ></div>

</div>

<script>
(function() {
    'use strict';

    var URL_BRANCHES      = '{URL_AJAX_BRANCHES}';
    var URL_FOLDERS       = '{URL_AJAX_FOLDERS}';
    var URL_INSTALL       = '{URL_AJAX_INSTALL}';
    var URL_ACTIVATE      = '{URL_AJAX_ACTIVATE}';
    var URL_DEACTIVATE    = '{URL_AJAX_DEACTIVATE}';
    var URL_UNINSTALL     = '{URL_AJAX_UNINSTALL}';
    var URL_REPOS         = '{URL_AJAX_REPOS}';
    var URL_SAVE_REPOS    = '{URL_AJAX_SAVE_REPOS}';
    var URL_LOCAL_INSTALL = '{URL_AJAX_LOCAL_INSTALL}';
    var URL_RESTORE       = '{URL_AJAX_RESTORE}';
    var URL_BACKUP        = '{URL_AJAX_BACKUP}';
    var URL_IMPORT_BDD    = '{URL_AJAX_IMPORT_BDD}';
    var URL_REVIEW        = '{URL_AJAX_REVIEW}';
    var URL_LANG          = '{URL_AJAX_LANG}';
    var CSRF_TOKEN        = '{CSRF_TOKEN}';

    // Read labels from data-attributes (no escaping issues)
    var langEl = document.getElementById('pbtm-lang');
    var L = {
        loading:     langEl.dataset.loading,
        error:       langEl.dataset.error,
        none:        langEl.dataset.none,
        success:     langEl.dataset.success,
        errPrefix:   langEl.dataset.errPrefix,
        noSel:       langEl.dataset.noSel,
        active:      langEl.dataset.active,
        inactive:    langEl.dataset.inactive,
        notInst:     langEl.dataset.notInst,
        upToDate:    langEl.dataset.upToDate,
        updateAvail: langEl.dataset.updateAvail,
        unknown:     langEl.dataset.unknown,
        activate:    langEl.dataset.activate,
        deactivate:  langEl.dataset.deactivate,
        uninstall:   langEl.dataset.uninstall,
        localInstall:langEl.dataset.localInstall,
        confirm:     langEl.dataset.confirm,
        confirmSoft: langEl.dataset.confirmSoft,
        confirmHard: langEl.dataset.confirmHard,
        download:    langEl.dataset.restoreDownload,
        selectRepo:  langEl.dataset.selectRepo,
        importConfirm:   langEl.dataset.importbddConfirm,
        importSuccess:   langEl.dataset.importbddSuccess,
        importImporting: langEl.dataset.importbddImporting,
        importAction:    langEl.dataset.importbddAction,
    };

    // -----------------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------------
    function post(url, data, cb) {
        $.ajax({
            url:        url,
            type:       'post',
            data:       data,
            dataType:   'json',
            success:    cb,
            error: function() { cb({ success: false, error: 'Network error' }); }
        });
    }

    function setFeedback(msg, type) {
        var el = document.getElementById('pbtm-remote-feedback');
        el.className = 'pbtm-feedback pbtm-feedback-' + (type || 'info');
        el.textContent = msg;
        el.style.display = 'block';
        setTimeout(function() { el.style.display = 'none'; }, 5000);
    }

    function showEl(id) { document.getElementById(id).style.display = ''; }
    function hideEl(id) { document.getElementById(id).style.display = 'none'; }

    function esc(str) {
        return String(str)
            .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
            .replace(/"/g,'&quot;').replace(/'/g,'&#39;');
    }

    // -----------------------------------------------------------------------
    // Tabs
    // -----------------------------------------------------------------------
    var tabButtons  = document.querySelectorAll('.pbtm-tab');
    var tabContents = document.querySelectorAll('.pbtm-tab-content');
    var remoteLoaded  = false;
    var restoreLoaded = false;
    var importBddLoaded = false;

    tabButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var target = btn.dataset.tab;
            if (!target) return;

            tabButtons.forEach(function(b) { b.classList.remove('active'); });
            tabContents.forEach(function(c) { c.classList.remove('active'); });

            btn.classList.add('active');
            document.getElementById('pbtm-tab-' + target).classList.add('active');

            if (target === 'remote' && !remoteLoaded) {
                remoteLoaded = true;
                loadBranches();
            }
            if (target === 'restore' && !restoreLoaded) {
                loadRestoreList();
            }
            if (target === 'importbdd' && !importBddLoaded) {
                loadImportBddList();
            }
        });
    });

    // -----------------------------------------------------------------------
    // Remote repositories
    // -----------------------------------------------------------------------
    var repoSelect   = document.getElementById('pbtm-repo-select');
    var branchSelect = document.getElementById('pbtm-branch-select');
    var currentRepo  = null;

    function loadBranches() {
        var opt = repoSelect.options[repoSelect.selectedIndex];
        if (!opt || !opt.dataset.repo) return;
        try { currentRepo = JSON.parse(opt.dataset.repo); } catch(e) { return; }

        branchSelect.innerHTML = '<option>' + L.loading + '</option>';
        hideEl('pbtm-remote-modules');
        hideEl('pbtm-remote-none');
        hideEl('pbtm-remote-error');
        showEl('pbtm-remote-loading');

        post(URL_BRANCHES, { owner: currentRepo.owner, repo: currentRepo.repo, token: CSRF_TOKEN }, function(data) {
            hideEl('pbtm-remote-loading');
            if (!data.success) {
                showEl('pbtm-remote-error');
                return;
            }
            branchSelect.innerHTML = '';
            branchSelect.disabled = false;
            data.branches.forEach(function(b) {
                var o = document.createElement('option');
                o.value = b;
                o.textContent = b;
                branchSelect.appendChild(o);
            });
            loadFolders();
        });
    }

    function loadFolders() {
        if (!currentRepo) return;
        document.getElementById('pbtm-remote-tbody').innerHTML = '';
        hideEl('pbtm-remote-modules');
        hideEl('pbtm-remote-none');
        hideEl('pbtm-remote-error');
        showEl('pbtm-remote-loading');

        post(URL_FOLDERS, {
            owner:  currentRepo.owner,
            repo:   currentRepo.repo,
            branch: branchSelect.value,
            path:   currentRepo.path || '',
            token:  CSRF_TOKEN
        }, function(data) {
            hideEl('pbtm-remote-loading');
            if (!data.success) { showEl('pbtm-remote-error'); return; }
            if (!data.folders.length) { showEl('pbtm-remote-none'); return; }

            var tbody = document.getElementById('pbtm-remote-tbody');
            data.folders.forEach(function(item) {
                var statusLabel, statusClass;
                if (item.status === 'active')        { statusLabel = L.active;      statusClass = 'pbtm-status-active'; }
                else if (item.status === 'inactive')  { statusLabel = L.inactive;    statusClass = 'pbtm-status-inactive'; }
                else if (item.status === 'installed') { statusLabel = L.inactive;    statusClass = 'pbtm-status-inactive'; }
                else if (item.status === 'uptodate')  { statusLabel = L.upToDate;    statusClass = 'pbtm-status-active'; }
                else if (item.status === 'update')    { statusLabel = L.updateAvail; statusClass = 'pbtm-status-update'; }
                else                                  { statusLabel = L.unknown;     statusClass = ''; }

                var tr = document.createElement('tr');
                tr.innerHTML =
                    '<td class="pbtm-col-check"><input type="checkbox" class="pbtm-remote-check" value="' + esc(item.name) + '"></td>' +
                    '<td><strong>' + esc(item.name) + '</strong></td>' +
                    '<td>' + esc(item.version || '—') + '</td>' +
                    '<td>' + esc(item.local_version || '—') + '</td>' +
                    '<td><span class="pbtm-status ' + statusClass + '">' + statusLabel + '</span></td>';
                tbody.appendChild(tr);
            });
            showEl('pbtm-remote-modules');
        });
    }

    document.getElementById('pbtm-check-all').addEventListener('change', function() {
        document.querySelectorAll('.pbtm-remote-check').forEach(function(cb) { cb.checked = this.checked; }, this);
    });
    document.getElementById('pbtm-select-all').addEventListener('click', function() {
        document.querySelectorAll('.pbtm-remote-check').forEach(function(cb) { cb.checked = true; });
    });
    document.getElementById('pbtm-deselect-all').addEventListener('click', function() {
        document.querySelectorAll('.pbtm-remote-check').forEach(function(cb) { cb.checked = false; });
    });

    document.getElementById('pbtm-install-sel').addEventListener('click', function() {
        var selected = Array.from(document.querySelectorAll('.pbtm-remote-check:checked')).map(function(cb) { return cb.value; });
        if (!selected.length) { setFeedback(L.noSel, 'warn'); return; }

        post(URL_INSTALL, {
            modules: selected.join(','),
            owner:   currentRepo.owner,
            repo:    currentRepo.repo,
            branch:  branchSelect.value,
            path:    currentRepo.path || '',
            token:   CSRF_TOKEN
        }, function(d) {
            if (d.success) location.reload(); else setFeedback((d.error || L.errPrefix), 'error');
        });
    });

    // -----------------------------------------------------------------------
    // Local table: activate / deactivate / install / uninstall
    // -----------------------------------------------------------------------
    document.getElementById('pbtm-local-table').addEventListener('click', function(e) {
        var btn = e.target.closest('button[data-id]');
        if (!btn) return;
        var id  = btn.dataset.id;
        var tok = btn.dataset.token || CSRF_TOKEN;

        if (btn.classList.contains('pbtm-action-activate')) {
            post(URL_ACTIVATE, { id: id, token: tok }, function(d) {
                if (d.success) location.reload(); else setFeedback(d.error, 'error');
            });

        } else if (btn.classList.contains('pbtm-action-deactivate')) {
            post(URL_DEACTIVATE, { id: id, token: tok }, function(d) {
                if (d.success) location.reload(); else setFeedback(d.error, 'error');
            });

        } else if (btn.classList.contains('pbtm-action-local-install')) {
            post(URL_LOCAL_INSTALL, { id: id, token: tok }, function(d) {
                if (d.success) location.reload(); else setFeedback(d.error, 'error');
            });

        } else if (btn.classList.contains('pbtm-action-uninstall')) {
            var dropFiles  = btn.dataset.drop || '0';
            var confirmMsg = dropFiles === '1' ? L.confirmHard : L.confirmSoft;
            if (!confirm(confirmMsg)) return;
            post(URL_UNINSTALL, { id: id, token: tok, drop_files: dropFiles }, function(d) {
                if (d.success && d.warning) {
                    setFeedback(d.warning, 'warn');
                    setTimeout(function() { location.reload(); }, 5000);
                } else if (d.success) {
                    location.reload();
                } else {
                    setFeedback(d.error, 'error');
                }
            });
        }
    });

    // -----------------------------------------------------------------------
    // Add repository from remote tab
    // -----------------------------------------------------------------------
    var addPanel    = document.getElementById('pbtm-add-repo-panel');
    var addOrgInput = document.getElementById('pbtm-add-org');
    var addRepoSel  = document.getElementById('pbtm-add-repo-select');
    var addError    = document.getElementById('pbtm-add-repo-error');

    document.getElementById('pbtm-show-add-repo').addEventListener('click', function() {
        addPanel.style.display = addPanel.style.display === 'none' ? 'block' : 'none';
        if (addPanel.style.display === 'block') loadOrgRepos();
    });

    document.getElementById('pbtm-cancel-add-repo').addEventListener('click', function() {
        addPanel.style.display = 'none';
    });

    document.getElementById('pbtm-load-org-repos').addEventListener('click', loadOrgRepos);

    function loadOrgRepos() {
        var org = addOrgInput.value.trim();
        if (!org) return;
        addRepoSel.innerHTML = '<option>' + L.loading + '</option>';
        addRepoSel.disabled = true;
        post(URL_REPOS, { org: org, token: CSRF_TOKEN }, function(data) {
            addRepoSel.innerHTML = '<option value="">—</option>';
            if (data.success && data.repos && data.repos.length) {
                data.repos.forEach(function(r) {
                    var o = document.createElement('option');
                    o.value = r.name;
                    o.textContent = r.name + (r.description ? ' — ' + r.description : '');
                    addRepoSel.appendChild(o);
                });
            } else {
                addRepoSel.innerHTML = '<option value="">' + L.error + '</option>';
            }
            addRepoSel.disabled = false;
        });
    }

    document.getElementById('pbtm-confirm-add-repo').addEventListener('click', function() {
        var org   = addOrgInput.value.trim();
        var repo  = addRepoSel.value;
        var path  = document.getElementById('pbtm-add-path').value.trim();
        var label = document.getElementById('pbtm-add-label').value.trim();

        if (!org || !repo) {
            addError.textContent = L.selectRepo;
            addError.style.display = 'block';
            return;
        }
        addError.style.display = 'none';

        post(URL_SAVE_REPOS, { org: org, repo: repo, path: path, label: label, token: CSRF_TOKEN }, function(data) {
            if (data.success) {
                var o = document.createElement('option');
                o.value = data.repos.length - 1;
                o.dataset.repo = JSON.stringify(data.repos[data.repos.length - 1]);
                o.textContent = label || org + '/' + repo;
                repoSelect.appendChild(o);
                repoSelect.value = o.value;
                addPanel.style.display = 'none';
                loadBranches();
            } else {
                addError.textContent = data.error || L.error;
                addError.style.display = 'block';
            }
        });
    });

    // -----------------------------------------------------------------------
    // Init
    // -----------------------------------------------------------------------
    repoSelect.addEventListener('change', loadBranches);
    branchSelect.addEventListener('change', loadFolders);

    document.getElementById('pbtm-refresh-local').addEventListener('click', function() { location.reload(); });
    document.getElementById('pbtm-refresh-remote').addEventListener('click', function() { loadBranches(); });
    document.getElementById('pbtm-refresh-restore').addEventListener('click', function() {
        restoreLoaded = false;
        loadRestoreList();
    });
    document.getElementById('pbtm-refresh-importbdd').addEventListener('click', function() {
        importBddLoaded = false;
        loadImportBddList();
    });

    // -----------------------------------------------------------------------
    // Restore
    // -----------------------------------------------------------------------
    function loadRestoreList() {
        restoreLoaded = true;
        var tbody   = document.getElementById('pbtm-restore-tbody');
        var wrapper = document.getElementById('pbtm-restore-wrapper');
        var loading = document.getElementById('pbtm-restore-loading');
        var none    = document.getElementById('pbtm-restore-none');
        var error   = document.getElementById('pbtm-restore-error');

        tbody.innerHTML = '';
        wrapper.style.display = 'none';
        none.style.display    = 'none';
        error.style.display   = 'none';
        loading.style.display = '';

        post(URL_RESTORE, { action: 'list', token: CSRF_TOKEN }, function(data) {
            loading.style.display = 'none';
            if (!data.success) { error.style.display = ''; return; }
            if (!data.backups.length) { none.style.display = ''; return; }

            data.backups.forEach(function(b) {
                var statusLabel, statusClass;
                if (b.installed) {
                    statusLabel = L.active;  statusClass = 'pbtm-status-active';
                } else if (b.has_folder) {
                    statusLabel = L.inactive; statusClass = 'pbtm-status-inactive';
                } else {
                    statusLabel = L.notInst;  statusClass = 'pbtm-status-none';
                }

                var reinstallBtn = '';
                if (!b.installed && b.has_folder) {
                    reinstallBtn = '<button class="pbtm-btn pbtm-btn-ok pbtm-restore-reinstall" data-module="' + esc(b.module_id) + '" data-filename="' + esc(b.filename) + '" style="margin-left:.25rem;">' + L.localInstall + '</button>';
                }

                var tr = document.createElement('tr');
                tr.innerHTML =
                    '<td><strong>' + esc(b.module_id) + '</strong><br><small class="pbtm-id">' + esc(b.filename) + '</small></td>' +
                    '<td>' + esc(b.date) + '</td>' +
                    '<td>' + esc(b.size) + '</td>' +
                    '<td><span class="pbtm-status ' + statusClass + '">' + statusLabel + '</span></td>' +
                    '<td class="pbtm-actions">' +
                        '<a class="pbtm-btn pbtm-btn-secondary" href="' + URL_RESTORE + '?action=download&module_id=' + esc(b.module_id) + '&filename=' + esc(b.filename) + '&token=' + CSRF_TOKEN + '">' + L.download + '</a>' +
                        reinstallBtn +
                    '</td>';
                tbody.appendChild(tr);
            });

            tbody.querySelectorAll('.pbtm-restore-reinstall').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var mid      = btn.dataset.module;
                    var filename = btn.dataset.filename;
                    post(URL_RESTORE, { action: 'reinstall', module_id: mid, filename: filename, token: CSRF_TOKEN }, function(d) {
                        var fb = document.getElementById('pbtm-restore-feedback');
                        if (d.success && !d.warning) {
                            fb.className = 'pbtm-feedback pbtm-feedback-ok';
                            fb.textContent = L.success;
                            fb.style.display = '';
                            setTimeout(function() { location.reload(); }, 1500);
                        } else if (d.success && d.warning) {
                            fb.className = 'pbtm-feedback pbtm-feedback-warn';
                            fb.textContent = d.warning;
                            fb.style.display = '';
                            setTimeout(function() { location.reload(); }, 10000);
                        } else {
                            fb.className = 'pbtm-feedback pbtm-feedback-error';
                            fb.textContent = d.error || L.error;
                            fb.style.display = '';
                        }
                    });
                });
            });

            wrapper.style.display = '';
        });
    }

    // -----------------------------------------------------------------------
    // ImportBDD
    // -----------------------------------------------------------------------
    function loadImportBddList() {
        importBddLoaded = true;
        var tbody   = document.getElementById('pbtm-importbdd-tbody');
        var wrapper = document.getElementById('pbtm-importbdd-wrapper');
        var loading = document.getElementById('pbtm-importbdd-loading');
        var none    = document.getElementById('pbtm-importbdd-none');
        var error   = document.getElementById('pbtm-importbdd-error');

        tbody.innerHTML      = '';
        wrapper.style.display = 'none';
        none.style.display    = 'none';
        error.style.display   = 'none';
        loading.style.display = '';

        post(URL_IMPORT_BDD, { action: 'list', token: CSRF_TOKEN }, function(data) {
            loading.style.display = 'none';
            if (!data.success) { error.style.display = ''; return; }
            if (!data.modules.length) { none.style.display = ''; return; }

            data.modules.forEach(function(m) {
                var tablesList = m.db_tables.map(function(t) { return '<code>' + esc(t) + '</code>'; }).join('<br>');
                var rowspan    = m.files.length;

                m.files.forEach(function(f, idx) {
                    var tr = document.createElement('tr');
                    var html = '';

                    // Module + Tables du module : cellules fusionnées sur la première ligne seulement
                    if (idx === 0) {
                        html +=
                            '<td rowspan="' + rowspan + '" style="vertical-align:top"><strong>' + esc(m.module_id) + '</strong></td>' +
                            '<td rowspan="' + rowspan + '" style="vertical-align:top">' + tablesList + '</td>';
                    }

                    html +=
                        '<td>' + esc(f.filename) + ' <small>(' + esc(f.size) + ')</small></td>' +
                        '<td>' + esc(f.date) + '</td>' +
                        '<td class="pbtm-actions">' +
                            '<button class="pbtm-btn pbtm-btn-danger pbtm-importbdd-run"' +
                                ' data-module="' + esc(m.module_id) + '"' +
                                ' data-filename="' + esc(f.filename) + '">' +
                                L.importAction +
                            '</button>' +
                        '</td>';

                    tr.innerHTML = html;
                    tbody.appendChild(tr);
                });
            });

            tbody.querySelectorAll('.pbtm-importbdd-run').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var mid      = btn.dataset.module;
                    var filename = btn.dataset.filename;
                    var confirmMsg = L.importConfirm.replace('%s', mid + ' / ' + filename);
                    if (!confirm(confirmMsg)) return;

                    btn.disabled    = true;
                    btn.textContent = L.importImporting;

                    var fb = document.getElementById('pbtm-importbdd-feedback');
                    fb.style.display = 'none';

                    post(URL_IMPORT_BDD, { action: 'import', module_id: mid, filename: filename, token: CSRF_TOKEN }, function(d) {
                        btn.disabled    = false;
                        btn.textContent = L.importAction;

                        if (d.success) {
                            fb.className     = 'pbtm-feedback pbtm-feedback-ok';
                            fb.textContent   = d.message || L.importSuccess;
                            fb.style.display = '';
                            setTimeout(function() { fb.style.display = 'none'; }, 6000);
                        } else {
                            fb.className     = 'pbtm-feedback pbtm-feedback-error';
                            fb.textContent   = d.error || L.error;
                            fb.style.display = '';
                        }
                    });
                });
            });

            wrapper.style.display = '';
        });
    }

    // ==========================================================================
    // ONGLET 5 — REVUE DE FICHIERS
    // ==========================================================================
    (function initReview() {
        var rvLangEl = document.getElementById('pbtm-review-lang');
        if (!rvLangEl) return;

        var RL = {
            refreshSuccess: rvLangEl.dataset.refreshSuccess,
            clearSuccess:   rvLangEl.dataset.clearSuccess,
            error:          rvLangEl.dataset.error,
            incompatible:   rvLangEl.dataset.incompatible,
            colFile:        rvLangEl.dataset.colFile,
            colModule:      rvLangEl.dataset.colModule,
            colItem:        rvLangEl.dataset.colItem,
            colEdit:        rvLangEl.dataset.colEdit,
            colContext:     rvLangEl.dataset.colContext,
            colUser:        rvLangEl.dataset.colUser,
            colDate:        rvLangEl.dataset.colDate,
            colSize:        rvLangEl.dataset.colSize,
        };

        var sectionTitles = {
            onserver:        rvLangEl.dataset.sectionOnserver,
            inupload:        rvLangEl.dataset.sectionInupload,
            incontent:       rvLangEl.dataset.sectionIncontent,
            allunused:       rvLangEl.dataset.sectionAllunused,
            usednoserver:    rvLangEl.dataset.sectionUsednoserver,
            unuseduser:      rvLangEl.dataset.sectionUnuseduser,
            orphan:          rvLangEl.dataset.sectionOrphan,
            ingalleryfolder: rvLangEl.dataset.sectionIngalleryfolder,
            ingallerytable:  rvLangEl.dataset.sectionIngallerytable,
            nogalleryfolder: rvLangEl.dataset.sectionNogalleryfolder,
            nogallerytable:  rvLangEl.dataset.sectionNogallerytable,
        };

        var cntIds = {
            files_on_server:         'rv-cnt-onserver',
            files_in_upload:         'rv-cnt-inupload',
            files_in_content:        'rv-cnt-incontent',
            all_unused:              'rv-cnt-allunused',
            used_not_on_server:      'rv-cnt-usednoserver',
            unused_with_users:       'rv-cnt-unuseduser',
            orphan:                  'rv-cnt-orphan',
            files_in_gallery_folder: 'rv-cnt-galleryfolder',
            files_in_gallery_table:  'rv-cnt-gallerytable',
            not_in_gallery_folder:   'rv-cnt-nogalleryfolder',
            not_in_gallery_table:    'rv-cnt-nogallerytable',
        };

        var btnRefresh  = document.getElementById('pbtm-review-refresh');
        var btnClear    = document.getElementById('pbtm-review-clear');
        var divLoading  = document.getElementById('pbtm-review-loading');
        var divFeedback = document.getElementById('pbtm-review-feedback');
        var divCounters = document.getElementById('pbtm-review-counters');
        var detailDiv   = document.getElementById('pbtm-rv-detail');
        var detailTitle = detailDiv.querySelector('.pbtm-rv-detail-title');
        var detailThead = detailDiv.querySelector('.pbtm-rv-thead');
        var detailTbody = detailDiv.querySelector('.pbtm-rv-tbody');

        function esc(s) {
            return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        function rvFeedback(msg, type) {
            divFeedback.className     = 'pbtm-feedback pbtm-feedback-' + type;
            divFeedback.textContent   = msg;
            divFeedback.style.display = '';
        }

        function updateCounters(counters) {
            var totalErrors = 0;
            Object.keys(cntIds).forEach(function(key) {
                var el = document.getElementById(cntIds[key]);
                if (!el || counters[key] === undefined) return;
                el.textContent = counters[key];
                if (key === 'used_not_on_server' || key === 'unused_with_users' || key === 'orphan')
                    totalErrors += parseInt(counters[key], 10) || 0;
            });
            var totalEl = document.getElementById('rv-cnt-total-errors');
            if (totalEl) totalEl.textContent = totalErrors;
            divCounters.style.display = '';
        }

        function closeDetail() {
            detailDiv.style.display = 'none';
            document.querySelectorAll('.pbtm-review-section-btn').forEach(function(b) {
                b.classList.remove('active');
            });
        }

        // --- Tooltips : affichage au survol de l'icône œil ---
        var tipEl = document.createElement('div');
        tipEl.className = 'pbtm-rv-tooltip';
        document.body.appendChild(tipEl);

        document.querySelectorAll('.pbtm-rv-tip').forEach(function(icon) {
            icon.addEventListener('mouseenter', function(e) {
                tipEl.textContent   = icon.dataset.tip || '';
                tipEl.style.display = 'block';
                var r = icon.getBoundingClientRect();
                tipEl.style.left = (r.left + window.scrollX) + 'px';
                tipEl.style.top  = (r.bottom + window.scrollY + 6) + 'px';
            });
            icon.addEventListener('mouseleave', function() {
                tipEl.style.display = 'none';
            });
            // Prevent the click from bubbling to the parent section button
            icon.addEventListener('click', function(e) { e.stopPropagation(); });
        });

        // --- Close detail ---
        detailDiv.querySelector('.pbtm-rv-detail-close').addEventListener('click', closeDetail);

        // --- Clear ---
        btnClear.addEventListener('click', function() {
            btnClear.disabled         = true;
            divLoading.style.display  = '';
            divFeedback.style.display = 'none';
            closeDetail();

            post(URL_REVIEW, { action: 'clear', token: CSRF_TOKEN }, function(d) {
                divLoading.style.display = 'none';
                btnClear.disabled        = false;
                if (d && d.success) {
                    Object.keys(cntIds).forEach(function(k) {
                        var el = document.getElementById(cntIds[k]);
                        if (el) el.textContent = '0';
                    });
                    var totalEl = document.getElementById('rv-cnt-total-errors');
                    if (totalEl) totalEl.textContent = '0';
                    divCounters.style.display = '';
                    rvFeedback(RL.clearSuccess, 'ok');
                    setTimeout(function() { divFeedback.style.display = 'none'; }, 5000);
                } else {
                    rvFeedback((d && d.error) ? d.error : RL.error, 'error');
                }
            });
        });

        // --- Refresh ---
        btnRefresh.addEventListener('click', function() {
            btnRefresh.disabled       = true;
            divLoading.style.display  = '';
            divFeedback.style.display = 'none';
            closeDetail();

            post(URL_REVIEW, { action: 'refresh', token: CSRF_TOKEN }, function(d) {
                divLoading.style.display = 'none';
                btnRefresh.disabled      = false;
                if (d && d.success) {
                    updateCounters(d.counters);
                    rvFeedback(RL.refreshSuccess, 'ok');
                    setTimeout(function() { divFeedback.style.display = 'none'; }, 5000);
                } else {
                    rvFeedback((d && d.error) ? d.error : RL.error, 'error');
                }
            });
        });

        // --- Section buttons ---
        document.querySelectorAll('.pbtm-review-section-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.pbtm-review-section-btn').forEach(function(b) {
                    b.classList.remove('active');
                });
                btn.classList.add('active');
                loadSection(btn.dataset.section);
            });
        });

        function loadSection(section) {
            detailTitle.textContent  = sectionTitles[section] || section;
            detailThead.innerHTML    = '';
            detailTbody.innerHTML    = '<tr><td><span class="pbtm-spinner"></span></td></tr>';
            detailDiv.style.display  = '';
            detailDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

            post(URL_REVIEW, { action: 'section', section: section, token: CSRF_TOKEN }, function(d) {
                if (!d || !d.success) {
                    detailTbody.innerHTML = '<tr><td class="pbtm-error">' + esc((d && d.error) || RL.error) + '</td></tr>';
                    return;
                }
                renderSection(section, d.rows);
            });
        }

        function renderSection(section, rows) {
            detailThead.innerHTML = '';
            detailTbody.innerHTML = '';

            if (!rows || rows.length === 0) {
                detailTbody.innerHTML = '<tr><td class="pbtm-info">—</td></tr>';
                return;
            }

            var cols    = columnsFor(section);
            var headRow = document.createElement('tr');
            cols.forEach(function(col) {
                var th = document.createElement('th');
                th.textContent = col.label;
                headRow.appendChild(th);
            });
            detailThead.appendChild(headRow);

            rows.forEach(function(row) {
                var tr = document.createElement('tr');
                cols.forEach(function(col) {
                    var td = document.createElement('td');
                    td.innerHTML = col.render(row);
                    tr.appendChild(td);
                });
                detailTbody.appendChild(tr);
            });
        }

        function fileCell(row) {
            var name   = esc(row.file || '');
            var folder = esc(row.folder || 'upload');
            var preview = '';
            if (row.is_picture)
                preview = ' <span class="rv-preview"><i class="fa fa-eye"></i><img src="{PATH_TO_ROOT}/' + folder + '/' + name + '" /></span>';
            else if (row.is_pdf)
                preview = ' <span class="rv-preview"><i class="fa fa-eye"></i><embed src="{PATH_TO_ROOT}/' + folder + '/' + name + '" /></span>';
            return '<span class="rv-filename">' + name + '</span>' + preview;
        }

        function columnsFor(section) {
            var fileCol      = { label: RL.colFile,   render: fileCell };
            var plainFileCol = { label: RL.colFile,   render: function(r) { return esc(r.file || ''); } };
            var moduleCol    = { label: RL.colModule, render: function(r) { return esc(r.module_source || ''); } };
            var itemCol      = { label: RL.colItem,   render: function(r) {
                if (r.item_link)
                    return '<a href="' + esc(r.item_link) + '" target="_blank">' + esc(r.item_title || '') + '</a>';
                return r.item_title ? esc(r.item_title) : '<span class="pbtm-info">' + esc(RL.incompatible) + '</span>';
            }};
            var editCol = { label: RL.colEdit, render: function(r) {
                if (!r.edit_link) return '—';
                return '<a class="pbtm-rv-edit-btn" href="' + esc(r.edit_link) + '" target="_blank">✎</a>';
            }};
            var contextCol = { label: RL.colContext, render: function(r) {
                if (!r.file_context) return '';
                // Bold the filename within the context string
                var ctx = esc(r.file_context);
                var fname = esc(r.file || '');
                if (fname) ctx = ctx.replace('«' + r.file + '»', '<strong class="pbtm-rv-ctx-hi">«' + fname + '»</strong>');
                return '<span class="pbtm-rv-context">' + ctx + '</span>';
            }};
            var userCol = { label: RL.colUser, render: function(r) { return esc(r.user || ''); } };
            var dateCol = { label: RL.colDate, render: function(r) { return esc(r.upload_date || ''); } };
            var sizeCol = { label: RL.colSize, render: function(r) { return esc(r.file_size || ''); } };

            switch (section) {
                case 'incontent':    return [fileCol, moduleCol, itemCol];
                case 'usednoserver': return [plainFileCol, moduleCol, itemCol, contextCol, editCol];
                case 'unuseduser':   return [fileCol, userCol, dateCol, sizeCol];
                default:             return [fileCol];
            }
        }
    }());

}());

// TAB 6 - Lang Review
(function() {
    var urlLang   = document.getElementById('pbtm-lang-url').value;
    var csrfToken = document.getElementById('pbtm-lang-csrf').value;
    var LL        = document.getElementById('pbtm-lang-labels').dataset;
    var body      = document.getElementById('pbtm-lang-body');

    // modulesData: array of {name, languages[]}
    var modulesData = [];
    var currentModule = null;

    function langPost(data, cb) {
        data.token = csrfToken;
        var reqBody = Object.keys(data).map(function(k) {
            return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]);
        }).join('&');
        fetch(urlLang, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: reqBody
        })
        .then(function(r) { return r.text(); })
        .then(function(t) {
            var idx = t.indexOf('{');
            var jsonStr = idx >= 0 ? t.substring(idx) : '';
            try { cb(JSON.parse(jsonStr)); }
            catch(e) { cb({ success: false, error: '[' + t.length + ' chars] ' + t.substring(0, 300) }); }
        })
        .catch(function(e) { cb({ success: false, error: 'fetch: ' + e.message }); });
    }

    // Load module list immediately, then scan all for dashboard
    langPost({ action: 'lang_modules' }, function(d) {
        if (!d || !d.success) {
            var dash = document.getElementById('pbtm-lang-dashboard');
            dash.innerHTML = '<p class="pbtm-lang-error">' + (d && d.error ? d.error : LL.error) + '</p>';
            return;
        }
        modulesData = d.modules;
        buildDashboard(d.modules);
    });

    function selectModule(modName) {
        body.innerHTML = '';
        currentModule = modName;

        var modInfo = null;
        for (var i = 0; i < modulesData.length; i++) {
            if (modulesData[i].name === modName) { modInfo = modulesData[i]; break; }
        }
        var langs = modInfo ? modInfo.languages : ['french'];

        if (langs.length <= 1) {
            analyzeAndRender(modName, langs[0] || 'french', body);
        } else {
            renderLangSubTabs(modName, langs);
        }
    }

    // ---- Dashboard: scan all modules for the reference lang (french) ----
    function buildDashboard(modules) {
        var dash = document.getElementById('pbtm-lang-dashboard');
        dash.innerHTML = '<p class="pbtm-lang-info">Analyse globale en cours…</p>';

        var results = [];
        var total = modules.length;
        var done = 0;

        if (!total) { dash.innerHTML = ''; return; }

        modules.forEach(function(m) {
            var refLang = m.languages[0] || 'french';
            langPost({ action: 'lang_analyze', module: m.name, lang: refLang }, function(d) {
                done++;
                if (d.success) {
                    var anomalies = (d.unused ? d.unused.length : 0)
                                  + (d.duplicates_internal ? d.duplicates_internal.length : 0)
                                  + (d.duplicates_external ? d.duplicates_external.length : 0);
                    if (anomalies > 0) {
                        results.push({ name: m.name, lang: refLang, unused: d.unused.length,
                            dup_int: d.duplicates_internal.length, dup_ext: d.duplicates_external.length,
                            total: anomalies });
                    }
                }
                if (done === total) renderDashboard(dash, results, total);
            });
        });
    }

    function renderDashboard(dash, results, total) {
        dash.innerHTML = '';

        if (!results.length) {
            var ok = document.createElement('p');
            ok.className = 'pbtm-lang-dash-ok';
            ok.textContent = '✔ Aucune anomalie détectée sur les ' + total + ' modules analysés';
            dash.appendChild(ok);
            return;
        }

        results.sort(function(a, b) { return b.total - a.total; });

        var header = document.createElement('p');
        header.className = 'pbtm-lang-dash-header';
        header.innerHTML = '<strong>' + results.length + '</strong> module'
            + (results.length > 1 ? 's' : '') + ' avec anomalies sur ' + total + ' analysés';
        dash.appendChild(header);

        var list = document.createElement('div');
        list.className = 'pbtm-lang-dash-list';

        results.forEach(function(r) {
            var row = document.createElement('div');
            row.className = 'pbtm-lang-dash-row';

            var a = document.createElement('a');
            a.className = 'pbtm-lang-dash-modname';
            a.textContent = r.name;

            var badge = document.createElement('a');
            badge.className = 'pbtm-lang-dash-badge';
            badge.textContent = r.total;
            badge.href = '#';
            badge.addEventListener('click', function(e) {
                e.preventDefault();
                selectModule(r.name);
                body.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });

            row.appendChild(a);
            row.appendChild(badge);
            list.appendChild(row);
        });

        dash.appendChild(list);
    }

    function renderLangSubTabs(modName, langs) {
        // Sub-tab bar
        var tabBar = document.createElement('div');
        tabBar.className = 'pbtm-lang-subtabs';

        var tabContent = document.createElement('div');
        tabContent.className = 'pbtm-lang-subtab-content';

        langs.forEach(function(lang, idx) {
            var btn = document.createElement('button');
            btn.className = 'pbtm-lang-subtab' + (idx === 0 ? ' active' : '');
            btn.textContent = langLabel(lang);
            btn.dataset.lang = lang;
            btn.addEventListener('click', function() {
                tabBar.querySelectorAll('.pbtm-lang-subtab').forEach(function(b) { b.classList.remove('active'); });
                btn.classList.add('active');
                tabContent.innerHTML = '<p class="pbtm-lang-info">' + LL.analyzing + '</p>';
                analyzeAndRender(modName, lang, tabContent);
            });
            tabBar.appendChild(btn);
        });

        body.appendChild(tabBar);
        body.appendChild(tabContent);

        // Auto-trigger first tab
        tabContent.innerHTML = '<p class="pbtm-lang-info">' + LL.analyzing + '</p>';
        analyzeAndRender(modName, langs[0], tabContent);
    }

    function analyzeAndRender(modName, lang, container) {
        container.innerHTML = '<p class="pbtm-lang-info">' + LL.analyzing + '</p>';
        langPost({ action: 'lang_analyze', module: modName, lang: lang }, function(d) {
            if (!d.success) {
                container.innerHTML = '<p class="pbtm-lang-error">' + esc(d.error || LL.error) + '</p>';
                return;
            }
            container.innerHTML = '';
            var caption = document.createElement('p');
            caption.className = 'pbtm-lang-caption';
            caption.innerHTML = 'Analyse de la version <strong>' + esc(langLabel(lang)) + '</strong>'
                + ' du module <strong>' + esc(modName) + '</strong>';
            container.appendChild(caption);
            container.appendChild(buildUnused(d.unused));
            container.appendChild(buildDupInternal(d.duplicates_internal));
            if (d.duplicates_external && d.duplicates_external.length >= 0)
                container.appendChild(buildDupExternal(d.duplicates_external));
        });
    }

    function langLabel(lang) {
        var flags = { french: '🇫🇷 Français', english: '🇬🇧 English', spanish: '🇪🇸 Español',
                      german: '🇩🇪 Deutsch', italian: '🇮🇹 Italiano', portuguese: '🇵🇹 Português',
                      dutch: '🇳🇱 Nederlands', russian: '🇷🇺 Русский', arabic: '🇸🇦 العربية' };
        return flags[lang] || lang.charAt(0).toUpperCase() + lang.slice(1);
    }

    function locationSpan(fullPath, line) {
        if (!fullPath) return '';
        var s = fullPath.replace(/\\/g, '/');
        var m = s.match(/\/modules\/.+\/lang\/[^\/]+\/[^\/]+/);
        var short = m ? m[0] : s;
        return '<span class="pbtm-lang-location">' + esc(short) + (line ? ':' + line : '') + '</span>';
    }

    function keyCell(tr, key, value) {
        var td = tr.insertCell();
        td.className = 'pbtm-lang-key';
        td.innerHTML = "<code>$lang['" + esc(key) + "'] = '" + esc(value || '') + "';</code>";
    }

    function buildUnused(rows) {
        var wrap = section(LL.sectionUnused, rows.length);
        if (!rows.length) { wrap.appendChild(emptyMsg()); return wrap; }
        var t = makeTable([LL.colKey]);
        rows.forEach(function(r) {
            var tr = t.tBodies[0].insertRow();
            keyCell(tr, r.key, r.value);
        });
        wrap.appendChild(t);
        return wrap;
    }

    function buildDupInternal(rows) {
        var wrap = section(LL.sectionDupInternal, rows.length);
        if (!rows.length) { wrap.appendChild(emptyMsg()); return wrap; }
        var t = makeTable([LL.colKeys]);
        rows.forEach(function(r) {
            var tr = t.tBodies[0].insertRow();
            cell(tr, r.keys.map(function(k) {
                return "<code>$lang['" + esc(k) + "'] = '" + esc(r.value) + "';</code>";
            }).join('<br>'), true);
        });
        wrap.appendChild(t);
        return wrap;
    }

    function buildDupExternal(rows) {
        var wrap = section(LL.sectionDupExternal, rows.length);
        if (!rows.length) { wrap.appendChild(emptyMsg()); return wrap; }
        var t = makeTable([LL.colKey, LL.colMatches]);
        rows.forEach(function(r) {
            var tr = t.tBodies[0].insertRow();
            keyCell(tr, r.key, r.value);
            cell(tr, r.matches.map(function(m) {
                return '<span class="pbtm-lang-match-mod">' + esc(m.module) + '</span>'
                    + " <code>$lang['" + esc(m.key) + "']</code>";
            }).join('<br>'), true);
        });
        wrap.appendChild(t);
        return wrap;
    }

    function section(title, count) {
        var wrap = document.createElement('div');
        wrap.className = 'pbtm-lang-section';
        var h = document.createElement('h3');
        h.className = 'pbtm-lang-section-title';
        h.textContent = title + ' (' + count + ')';
        wrap.appendChild(h);
        return wrap;
    }

    function makeTable(headers) {
        var t = document.createElement('table');
        t.className = 'pbtm-table';
        var tr = t.createTHead().insertRow();
        headers.forEach(function(h) {
            var th = document.createElement('th');
            th.textContent = h;
            tr.appendChild(th);
        });
        t.createTBody();
        return t;
    }

    function cell(tr, content, isHtml) {
        var td = tr.insertCell();
        if (isHtml) td.innerHTML = content;
        else td.textContent = content;
    }

    function emptyMsg() {
        var p = document.createElement('p');
        p.className = 'pbtm-lang-none';
        p.textContent = LL.none;
        return p;
    }

    function esc(s) {
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

}());
</script>
