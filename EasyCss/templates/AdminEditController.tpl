		<nav id="admin-quick-menu">
                    <a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
                        <i class="fa fa-bars" aria-hidden="true"></i> {@module_title}
                    </a>
                    <ul>
                        <li>
                            <a href="${relative_url(EasyCssUrlBuilder::theme_choice())}" class="quick-link">{@theme_choice}</a>
                        </li>
                    </ul>
		</nav>

                # INCLUDE MSG #

		<div id="admin-contents">
                    <fieldset>
                        <legend>{FIELDSET_LEGEND}</legend>
                        # START errors #
                        <div class="error">
                            # INCLUDE errors.SUBTEMPLATE #
                        </div>
                        # END #

                        <div class="fieldset-inset">
                            <p class="fieldset-description">{@edit_description}</p>


                            <form id="AdminEasyCssEditController" method="post" class="fieldset-content">
                                # START elements #
                                    # INCLUDE elements.SUBTEMPLATE #
                                # END #


                                <input id="AdminEasyCssEditController_elements_fields" name="AdminEasyCssEditController_elements_fields" value="{ELEMENTS_FIELDS}" type="hidden">
                                <input id="AdminEasyCssEditController_token" name="token" value="{TOKEN}" type="hidden">
                                <fieldset id="AdminEasyCssEditController_fbuttons" class="fieldset-submit">
                                    <div class="fieldset-inset">
                                        <button type="submit" class="button submit" name="AdminEasyCssEditController_submit" onclick="" value="true">Envoyer</button>
                                        <button type="reset" class="button reset-button" value="true">Défaut</button>
                                    </div>
                                </fieldset>
                            </form>


                            # INCLUDE FORM #
                        </div>
                    </fieldset>
                </div>
