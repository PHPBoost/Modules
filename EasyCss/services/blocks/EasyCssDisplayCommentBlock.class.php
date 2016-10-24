<?php

/* #################################################
 *                           EasyCssTitleBlock.class.php
 *                            -------------------
 *   begin                : 2016/05/04
 *   copyright            : (C) 2016 PaperToss
 *   email                : t0ssp4p3r@gmail.com
 *
 *
  ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
  ################################################### */

/**
 * Commentaire affiché ( /** commentaire * / )
 *
 * @author PaperToss
 */
class EasyCssDisplayCommentBlock extends EasyCssAbstractBlock
{
    public $id;

    public $to_display = true;
    
    /** @var \EasyCssDisplayCommentField */
    protected $title;

    public function __construct($id, $parent_id, $title)
    {
        $this->title = new EasyCssDisplayCommentField($id, $title);
        $this->id = $id;
        $this->parent_id = $parent_id;
    }

    public function get_templates()
    {
        $title_tpl = $this->title->get_form(false);
        return array($title_tpl);
    }
    
    public function get_css_to_save()
    {
         
        if ($this->parent_id === '' || $this->parent_id === '/main')
        {
            return "\n" . '/**' . $this->title->get_title() . '*/' . "\n";
        }
        return '/**' . $this->title->get_title() . '*/' . "\n";
    }
    
}
