<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      LamPDL
 * @version     PHPBoost 6.0 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2026 03 01
 *
 * Admin config page: manage GitHub repos and optional token.
 */

class AdminDevToolsConfigController extends DefaultAdminModuleController
{
    public function execute(HTTPRequestCustom $request)
    {
        $this->build_form();

        if ($this->submit_button->has_been_submited() && $this->form->validate())
        {
            $this->save();
            $this->view->put('MESSAGE_HELPER', MessageHelper::display(
                $this->lang['warning.process.success'],
                MessageHelper::SUCCESS,
                4
            ));
        }

        $this->view->put('CONTENT', $this->form->display());
        return new DefaultAdminDisplayResponse($this->view);
    }

    private function build_form()
    {
        $this->config = DevToolsConfig::load();
        $form   = new HTMLForm(__CLASS__);
        $lang   = $this->lang;

        // --- GitHub token ---
        $fieldset_token = new FormFieldsetHTML('token_fieldset', $lang['devtools.config.github.token']);
        $form->add_fieldset($fieldset_token);
        $fieldset_token->add_field(new FormFieldTextEditor(
            'github_token',
            $lang['devtools.config.github.token'],
            $this->config->get_github_token(),
            ['description' => 'ghp_… — optional, increases the GitHub API rate limit (60 → 5,000 req/h)']
        ));

        // --- Repos ---
        $fieldset_repos = new FormFieldsetHTML('repos_fieldset', $lang['devtools.config.repos']);
        $form->add_fieldset($fieldset_repos);

        $repos      = $this->config->get_repos() ?: DevToolsConfig::DEFAULT_REPOS;
        $repos_json_html = htmlspecialchars(json_encode(array_values($repos)), ENT_QUOTES); // for HTML attribute
        $repos_json_js   = json_encode(array_values($repos), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); // for JS var
        $url_repos  = DevToolsUrlBuilder::ajax_repos()->rel();

        $l_add      = addslashes($lang['devtools.config.repo.add']);
        $l_delete   = addslashes($lang['devtools.config.repo.delete']);
        $l_org      = addslashes($lang['devtools.config.repo.org']);
        $l_pick     = addslashes($lang['devtools.config.repo.pick']);
        $l_path     = addslashes($lang['devtools.config.repo.path']);
        $l_label    = addslashes($lang['devtools.config.repo.label']);
        $l_loading  = addslashes($lang['devtools.remote.loading']);
        $l_error    = addslashes($lang['devtools.remote.error']);

        $html = <<<HTML
<div id="pbtm-repos-list"></div>

<div class="pbtm-add-repo-panel" style="border:1px solid #e5e7eb;border-radius:6px;padding:1rem;margin-top:.75rem;background:#f9fafb;">
    <strong>{$l_add}</strong>
    <div class="pbtm-repo-row" style="margin-top:.75rem;">
        <div class="pbtm-repo-field">
            <label>{$l_org}</label>
            <div style="display:flex;gap:.5rem;">
                <input type="text" id="pbtm-new-org" value="PHPBoost" style="flex:1;padding:.35rem .6rem;border:1px solid #d1d5db;border-radius:4px;font-size:14px;">
                <button type="button" id="pbtm-load-repos" class="pbtm-btn pbtm-btn-secondary">&#x21bb;</button>
            </div>
        </div>
        <div class="pbtm-repo-field">
            <label>{$l_pick}</label>
            <select id="pbtm-new-repo-select" style="width:100%;padding:.35rem .6rem;border:1px solid #d1d5db;border-radius:4px;font-size:14px;">
                <option value="">—</option>
            </select>
        </div>
        <div class="pbtm-repo-field">
            <label>{$l_path}</label>
            <input type="text" id="pbtm-new-path" placeholder="modules" style="width:100%;padding:.35rem .6rem;border:1px solid #d1d5db;border-radius:4px;font-size:14px;box-sizing:border-box;">
        </div>
        <div class="pbtm-repo-field">
            <label>{$l_label}</label>
            <input type="text" id="pbtm-new-label" placeholder="{$l_label}" style="width:100%;padding:.35rem .6rem;border:1px solid #d1d5db;border-radius:4px;font-size:14px;box-sizing:border-box;">
        </div>
        <div class="pbtm-repo-field" style="display:flex;align-items:flex-end;">
            <button type="button" id="pbtm-add-repo-btn" class="pbtm-btn pbtm-btn-ok" style="width:100%;">+ {$l_add}</button>
        </div>
    </div>
    <p id="pbtm-repos-feedback" style="display:none;margin:.5rem 0 0;font-size:13px;color:#dc2626;"></p>
</div>

<input type="hidden" name="repos_json" id="pbtm-repos-json" value="{$repos_json_html}">

<style>
.pbtm-repo-row { display:flex; flex-wrap:wrap; gap:.75rem; margin-bottom:.5rem; }
.pbtm-repo-row label { display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:.2rem; }
.pbtm-repo-field { flex:1; min-width:140px; }
.pbtm-repo-item { display:flex; align-items:center; justify-content:space-between; padding:.5rem .75rem; border:1px solid #e5e7eb; border-radius:4px; margin-bottom:.4rem; background:#fff; }
.pbtm-repo-item-name { font-weight:600; font-size:14px; }
.pbtm-repo-item-path { font-size:12px; color:#6b7280; margin-left:.5rem; }
.pbtm-repo-item-del { background:#dc2626; color:#fff; border:none; border-radius:4px; padding:.2rem .5rem; cursor:pointer; font-size:13px; }
</style>

<script>
(function(){
    var URL_REPOS = '{$url_repos}';
    var repos = {$repos_json_js};

    function renderList() {
        var list = document.getElementById('pbtm-repos-list');
        list.innerHTML = '';
        if (!repos.length) {
            list.innerHTML = '<p style="color:#6b7280;font-style:italic;margin:.5rem 0;">No repository configured.</p>';
        }
        repos.forEach(function(repo, idx) {
            var item = document.createElement('div');
            item.className = 'pbtm-repo-item';
            item.innerHTML =
                '<span>' +
                    '<span class="pbtm-repo-item-name">' + esc(repo.label || repo.owner+'/'+repo.repo) + '</span>' +
                    '<span class="pbtm-repo-item-path">' + esc(repo.owner+'/'+repo.repo) + (repo.path ? ' → '+esc(repo.path) : '') + '</span>' +
                '</span>' +
                '<button type="button" class="pbtm-repo-item-del" data-idx="'+idx+'">✕</button>';
            list.appendChild(item);
        });
        list.querySelectorAll('.pbtm-repo-item-del').forEach(function(btn) {
            btn.addEventListener('click', function() {
                repos.splice(parseInt(btn.dataset.idx), 1);
                renderList();
                sync();
            });
        });
        sync();
    }

    function sync() {
        document.getElementById('pbtm-repos-json').value = JSON.stringify(repos);
    }

    function esc(s) { return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

    // Load repos from GitHub org
    document.getElementById('pbtm-load-repos').addEventListener('click', function() {
        var org = document.getElementById('pbtm-new-org').value.trim();
        if (!org) return;
        var select = document.getElementById('pbtm-new-repo-select');
        select.innerHTML = '<option>{$l_loading}</option>';
        select.disabled = true;

        jQuery.ajax({
            url: URL_REPOS, type: 'post',
            data: { org: org },
            dataType: 'json',
            success: function(data) {
                select.innerHTML = '<option value="">—</option>';
                if (data.success && data.repos.length) {
                    data.repos.forEach(function(r) {
                        var o = document.createElement('option');
                        o.value = r.name;
                        o.textContent = r.name + (r.description ? ' — ' + r.description : '');
                        select.appendChild(o);
                    });
                } else {
                    select.innerHTML = '<option value="">{$l_error}</option>';
                }
                select.disabled = false;
            },
            error: function() {
                select.innerHTML = '<option value="">{$l_error}</option>';
                select.disabled = false;
            }
        });
    });

    // Auto-load PHPBoost repos on page load
    document.getElementById('pbtm-load-repos').click();

    // Add repo
    document.getElementById('pbtm-add-repo-btn').addEventListener('click', function() {
        var org    = document.getElementById('pbtm-new-org').value.trim();
        var repo   = document.getElementById('pbtm-new-repo-select').value;
        var path   = document.getElementById('pbtm-new-path').value.trim();
        var label  = document.getElementById('pbtm-new-label').value.trim();
        var fb     = document.getElementById('pbtm-repos-feedback');

        if (!org || !repo) { fb.textContent = 'Please select a repository.'; fb.style.display='block'; return; }
        fb.style.display = 'none';

        repos.push({ label: label || org+'/'+repo, owner: org, repo: repo, path: path });
        renderList();

        // reset
        document.getElementById('pbtm-new-repo-select').value = '';
        document.getElementById('pbtm-new-path').value = '';
        document.getElementById('pbtm-new-label').value = '';
    });

    renderList();
})();
</script>
HTML;

        $fieldset_repos->add_field(new FormFieldHTML('repos_ui', $html));

        $this->submit_button = new FormButtonDefaultSubmit();
        $form->add_button($this->submit_button);
        $form->add_button(new FormButtonReset());

        $this->form = $form;
    }

    private function save()
    {
        $token = trim($this->form->get_value('github_token'));
        $this->config->set_github_token($token);

        $json  = $this->form->get_value('repos_json') ?? '';
        $repos = !empty($json) ? json_decode($json, true) : [];

        if (is_array($repos))
        {
            $clean = [];
            foreach ($repos as $repo)
            {
                $owner = trim($repo['owner'] ?? '');
                $r     = trim($repo['repo']  ?? '');
                if ($owner && $r)
                {
                    $clean[] = [
                        'label' => trim($repo['label'] ?? '') ?: $owner.'/'.$r,
                        'owner' => $owner,
                        'repo'  => $r,
                        'path'  => trim($repo['path'] ?? ''),
                    ];
                }
            }
            if (!empty($clean))
                $this->config->set_repos($clean);
        }

        $this->config->persist();

        HooksService::execute_hook_action(
            'edit_config',
            self::$module_id,
            [
                'title' => StringVars::replace_vars($this->lang['form.module.title'], ['module_name' => self::get_module_configuration()->get_name()]),
                'url'   => ModulesUrlBuilder::configuration()->rel(),
            ]
        );
    }

}
?>
