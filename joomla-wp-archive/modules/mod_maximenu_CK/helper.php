<?php

/**
 * @copyright	Copyright (C) 2010 Cédric KEIFLIN alias ced1870
 * http://www.ck-web-creation-alsace.com
 * http://www.joomlack.fr
 * Module Maximenu_CK for Joomla! 1.5
 * @license		GNU/GPL
 * 
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');

class modmaximenu_CKHelper {

    function GetMenu(&$params) {
        jimport('joomla.application.module.helper');
		jimport('joomla.filesystem.file');
        JHTML::_("behavior.mootools");


        // retrieve parameters from the module
        $mooduree = $params->get('mooduration', 500);
        $mootransition = $params->get('mootransition', 'Bounce');
        $mooease = $params->get('mooease', 'easeOut');
        $usemootools = $params->get('usemootools', '1');
        $orientation = $params->get('orientation', '0');
        $testoverflow = $params->get('testoverflow', '1');
        $usecss = $params->get('usecss', '1');
        $menuID = $params->get('menuid', 'maximenuCK');
        $usefancy = $params->get('usefancy', '1');
        $fancyduree = $params->get('fancyduration', 500);
        $fancytransition = $params->get('fancytransition', 'Sine');
        $fancyease = $params->get('fancyease', 'easeOut');
        $theme = $params->get('theme', 'default');
        $fxtype = $params->get('fxtype', 'open');
        $useopacity = $params->get('useopacity', '0');
        $dureeout = $params->get('dureeout', 500);
        $startlevel = $params->get('startLevel', '0');
        $endlevel = $params->get('endLevel', '10');
        $dependantitems = $params->get('dependantitems', '0');
        $style = $params->get('style', 'moomenu');
        $routehomepage = $params->get('routehomepage', '0');
        $opentype = $params->get('opentype', 'open');
        $direction = $params->get('direction', 'normal');
        $directionoffset1 = $params->get('directionoffset1', '30');
        $directionoffset2 = $params->get('directionoffset2', '30');
		



        // detection for mobiles
        if (isset($_SERVER['HTTP_USER_AGENT']) && (strstr($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'], 'iPad') || strstr($_SERVER['HTTP_USER_AGENT'], 'iPod') || strstr($_SERVER['HTTP_USER_AGENT'], 'Android')))
            $style = 'click';

        // add external stylesheets
        $document = &JFactory::getDocument();
        if ($orientation == 1) {
            $document->addStyleSheet(JURI::base() . 'modules/mod_maximenu_CK/themes/' . $theme . '/css/moo_maximenuV_CK.css');
            if ($usecss == 1)
                $document->addStyleSheet(JURI::base() . 'modules/mod_maximenu_CK/themes/' . $theme . '/css/maximenuV_CK.php?monid=' . $menuID);
        } else {
            $document->addStyleSheet(JURI::base() . 'modules/mod_maximenu_CK/themes/' . $theme . '/css/moo_maximenuH_CK.css');
            if ($usecss == 1)
                $document->addStyleSheet(JURI::base() . 'modules/mod_maximenu_CK/themes/' . $theme . '/css/maximenuH_CK.php?monid=' . $menuID);
        }



        // add mootools effects
        if ($usemootools == 1) {

            if ($fxtype == 'open') {
                $document->addScript(JURI::base() . 'modules/mod_maximenu_CK/assets/moo_maximenu_CK.js');
            } else {
                $document->addScript(JURI::base() . 'modules/mod_maximenu_CK/assets/moo_maximenuSlide_CK.js');
            }

            // load moomenu 
            $js = "window.addEvent('domready', function() {new DropdownMaxiMenu(document.getElement('div#" . $menuID . "'),{"
                    . "mooTransition : '" . $mootransition . "',"
                    . "mooEase : '" . $mooease . "',"
                    . "useOpacity : '" . $useopacity . "',"
                    . "dureeOut : " . $dureeout . ","
                    . "menuID : '" . $menuID . "',"
                    . "testoverflow : '" . $testoverflow . "',"
                    . "orientation : '" . $orientation . "',"
                    . "style : '" . $style . "',"
                    . "opentype : '" . $opentype . "',"
                    . "direction : '" . $direction . "',"
                    . "directionoffset1 : '" . $directionoffset1 . "',"
                    . "directionoffset2 : '" . $directionoffset2 . "',"
                    . "mooDuree : " . $mooduree . "});"
                    . "});";

            $document->addScriptDeclaration($js);
        } else {
            $document->addStyleSheet(JURI::base() . 'modules/mod_maximenu_CK/assets/maximenu_CK.css');
            $script = '<!--
                        window.addEvent(\'domready\', function() {
                        var sfEls = document.getElementById("' . $menuID . '").getElementsByTagName("li");
                        for (var i=0; i<sfEls.length; i++) {
	
                            sfEls[i].onmouseover=function() {
                                this.className+=" sfhover";
                            }
		
                            sfEls[i].onmouseout=function() {
                                this.className=this.className.replace(new RegExp(" sfhover\\\\b"), "");
                            }
                        }
                        });
                        //-->';
            $document->addScriptDeclaration($script);
        }

        // add fancy effect
        if ($usemootools == 1 && $orientation != 1 && $usefancy == 1) {
            $document->addScript(JURI::base() . 'modules/mod_maximenu_CK/assets/fancymenu_CK.js');

            $js = "window.addEvent('domready', function() {new SlideList(document.getElement('div#" . $menuID . " ul'),{"
                    . "fancyTransition : '" . $fancytransition . "',"
                    . "fancyEase : '" . $fancyease . "',"
                    . "fancyDuree : " . $fancyduree . "});"
                    . "});";

            $document->addScriptDeclaration($js);
        }

        // add javascript from the theme
        $document->addScript(JURI::base() . 'modules/mod_maximenu_CK/themes/' . $theme . '/js/maximenu_addon_CK.js');

        // load from the database
        $menutype = $params->get('menutype', 'mainmenu');
        $db = & JFactory::getDBO();
        $query = "
			SELECT *
			FROM #__menu
			WHERE menutype='" . $menutype . "' AND published=1
			ORDER BY sublevel DESC,ordering
			;";
        $db->setQuery($query);
        $rows = $db->loadObjectList('id');

        // get current user
        $user = & JFactory::getUser();
        $urights = $user->get('aid', 0);

        // get active item ID
        $menu = &JSite::getMenu();
        $active = $menu->getActive();
        $path = isset($active) ? $active->tree : array();

        // list all modules
        $modulesList = modmaximenu_CKHelper::CreateModulesList();

        // creates menu nodes
        $level = 0;
        $items = array();
        $i = 0;

        // page title management
        if ($active) {
            $pagetitle = $document->getTitle();
            $title = $pagetitle;
            if (preg_match("/||/", $active->name)) {
                $title = explode("||", $active->name);
                $title = str_replace($active->name, $title[0], $pagetitle);
            }
            if (preg_match("/\[/", $active->name)) {
                if (!$title)
                    $title = $active->name;
                $title = explode("[", $title);
                $title = str_replace($active->name, $title[0], $pagetitle);
            }
            $document->setTitle($title); // returns the page title without description
        }


        foreach ($rows as $k => &$item) {

            // saves childs into parents items
            if ($item->sublevel > 0) {
                $rows[$item->parent]->haschild = 'yes';
                if (isset($item->haschild)) {
                    $rows[$item->parent]->enfants.=$item->id . '|' . $item->enfants;
                } else {
                    $rows[$item->parent]->enfants.=$item->id . '|';
                }


                // add parent-child classes
                if (isset($active) && $active->id == $item->id) {


                    $j = $item->sublevel;

                    $tempitemID = $item->parent;

                    while ($j != 0) {

                        $rows[$tempitemID]->classe .= " active";

                        $tempitemID = $rows[$tempitemID]->parent;

                        $j--;
                    }
                }
                if (isset($item->haschild)) {
                    $item->classe .= " parent";
                }
            }
            // create childs after respective parent
            if ($item->sublevel == 0 && $urights >= $item->access) { //gestion des droits des parents niveau 0
                $items[$i] = $item;
                if (isset($item->haschild)) {
                    $item->classe .= " parent";
                    $childs = explode("|", $item->enfants);
                    foreach ($childs as $c) {
                        if ($c) {
                            $i++;

                            // manage rights for childs
                            if (($urights >= $rows[$rows[$c]->parent]->access) && ($urights >= $rows[$c]->access)) {
                                $items[$i] = $rows[$c];
                            } else {
                                $i--;
                            }
                        }
                    }
                }
            } else {
                $i--;
            }
            $i++;
        }


        $lastitem = 0;


        // first loop to delete subitems not under active element
        foreach ($items as $i => &$item) {

            // looking for start and end level
            if (($item->sublevel < $startlevel && $startlevel) || ($item->sublevel > $endlevel)) {
                unset($items[$i]);
                continue;
            }

            // check if item is child of active element
            if ($dependantitems && $startlevel && (
                    ( ($item->sublevel == $active->sublevel + 1) && ($item->parent != $active->id) )
                    || ( isset($items[$lastitem]) && ($item->parent != $items[$lastitem]->id) && ($item->parent != $items[$lastitem]->parent) && ($item->parent != $active->id) )
                    || (!isset($items[$lastitem]) && ($item->parent != $active->id) )
                    )) {
                //if ($dependantitems && $startlevel && !in_array($item->parent, $path) ) {
                unset($items[$i]);
                continue;
            }

            $lastitem = $i;
        }


        $niveau_premier = 0; // niveau du premier élément du menu double
        $lastitem = 0;
        foreach ($items as $i => &$item) {
            if (!$niveau_premier) {
                $niveau_premier = $items[$i]->sublevel; // important for split menu
                $item->classe .= ' first';
            }

            $item->deeper = false;
            $item->shallower = false;
            $item->level_diff = 0;

            if (isset($items[$lastitem])) {
                $items[$lastitem]->deeper = ($item->sublevel > $items[$lastitem]->sublevel);
                $items[$lastitem]->shallower = ($item->sublevel < $items[$lastitem]->sublevel);
                $items[$lastitem]->level_diff = ($items[$lastitem]->sublevel - $item->sublevel);


                // add classes for first an last items
                if ($items[$lastitem]->deeper || isset($items[$lastitem]->colonne))
                    $item->classe .= ' first';
                if ($items[$lastitem]->shallower || $item->name == '[col]')
                    $items[$lastitem]->classe .= ' last';
            }

            $lastitem = $i;

            $item->is_end = !isset($items[$i + 1]);
            if ($item->is_end) {
                if ($startlevel) {
                    $item->level_diff = $item->sublevel - $niveau_premier; //for split menu
                } else {
                    $item->level_diff = $item->sublevel;
                }
            }


            //pour J1.5
            $menu_params = new stdClass();
            $menu_params = new JParameter($item->params);
            $menu_secure = $menu_params->def('secure', 0);

            switch ($item->type) {
                case 'separator':
                    // No further action needed.
                    continue;

                case 'url':
                    if ((strpos($item->link, 'index.php?') === 0) && (strpos($item->link, 'Itemid=') === false)) {
                        // If this is an internal Joomla link, ensure the Itemid is set.
                        $item->link = $item->link . '&amp;Itemid=' . $item->id;
                    }
                    break;
                case 'menulink':
                    // If this is an alias use the item id stored in the parameters to make the link.
                    $alias = $menu->getItem($menu_params->def('menu_item', 0));
                    $item->link = $alias->link . '&amp;Itemid=' . $alias->id;
                    break;
                default:
                    $router = JSite::getRouter();
                    if ($router->getMode() == JROUTER_MODE_SEF) {
                        $item->link = 'index.php?Itemid=' . $item->id;
                    } else {
                        $item->link .= '&Itemid=' . $item->id;
                    }
                    break;
            }

            if ($item->home == 1) {
                // Correct the URL for the home page.
                if ($routehomepage) {
                    $item->link = JRoute::_($item->link);
                } else {
                    $item->link = JURI::base();
                }
            } elseif (strcasecmp(substr($item->link, 0, 4), 'http') && (strpos($item->link, 'index.php?') !== false)) {
                // This is an internal Joomla web site link.
                $item->link = JRoute::_($item->link, true, $menu_secure);
            } else {
                // Correct the & in the link.
                $item->link = str_replace('&', '&amp;', $item->link);
            }

            // add some classes
            $item->classe .= " item" . $item->id;
            if (isset($active) && $active->id == $item->id) {
                $item->classe .= " current active";
            }

            // make columns
            if (preg_match('/\[col\]/', $item->name)) {
                //$item->name = preg_replace('/\[col\]/', '', $item->name);
                $colparent = false;
                foreach ($items as $testcol) {
                    if ($testcol->id == $item->parent) {
                        $colparent = true;
                        break;
                    }
                }
                if ($colparent)
                    $item->colonne = true;
                $item->name = '';
            }

            $item->divclasse = "";
            if (preg_match('/\[cols([0-9])\]/', $item->name, $resultat)) {
                $item->name = preg_replace('/\[cols[0-9]\]/', '', $item->name);
                $divclasse = " cols" . $resultat[1];
                $item->divclasse = strval($divclasse);
            }


            // search for parameters
            $paramsCK = "";
            $masque = "/\[(.*?)\]/";
            /* if (preg_match($masque, $item->name, $resultat)) {
              $paramsCK = $resultat[1];var_dump($paramsCK);
              $titreCK = preg_replace('/\[' . $paramsCK . '\]/', '', $item->name);
              } else {
              $titreCK = $item->name;
              } */
            $paramsCKs = array();
            if (preg_match_all($masque, $item->name, $resultat)) {
                $paramsCKs = $resultat[1]; //var_dump($paramsCKs);
            }

            // variables definition
            $titreCK = $item->name;
            $item->content = "";
            $item->rel = "";

            foreach ($paramsCKs as $paramsCK) {



                $titreCK = preg_replace('/\[(.*)\]/', '', $item->name);


                // load module
                if (preg_match('/modname/', $paramsCK)) {
                    $item->content = '<div class="maximenuCK_mod">' . modmaximenu_CKHelper::GenModuleByName($paramsCK, $params) . '<div class="clr"></div></div>';
                } elseif (preg_match('/modid/', $paramsCK)) {
                    $item->content = '<div class="maximenuCK_mod">' . modmaximenu_CKHelper::GenModuleById($paramsCK, $params, $modulesList) . '<div class="clr"></div></div>';
                } else {
                    //$item->content = "";
                }


                // add rel attribute
                if (preg_match('/rel=/', $paramsCK)) {
                    $item->rel = ' rel="' . preg_replace('/rel=/', '', $paramsCK) . '"';
                }
            } // fin de la boucle foreach
            // manage title
            $titreCK = explode("||", $titreCK);
            if (isset($titreCK[1])) {
                //$titreCK = $titreCK[0] . '<span class="descCK">' . $titreCK[1] . '</span>';
                $item->desc = '<span class="descCK">' . $titreCK[1] . '</span>';
            } else {
                //$titreCK = $titreCK[0];
                $item->desc = '';
            }
            $titreCK = $titreCK[0];


            // manage images
            $item->image = '';
            $menu_image = $menu_params->def('menu_image', -1);
            $menu_image_hover = explode('.', $menu_image);
            $imagerollover = '';
            if (isset($menu_image_hover[1])) {
                $menu_image_hover = $menu_image_hover[0] . $params->get('imagerollprefix', '_hover') . '.' . $menu_image_hover[1];
                if (JFile::exists(JPATH_ROOT . '/images/stories/' . $menu_image_hover)) {
                    $imagerollover = ' onmouseover="javascript:this.src=\'' . JURI::base(true) . '/images/stories/' . $menu_image_hover . '\'" onmouseout="javascript:this.src=\'' . JURI::base(true) . '/images/stories/' . $menu_image . '\'"';
                }
            }
            if (($menu_image <> '-1') && $menu_image) {
                if ($params->get('menu_images_align') == 1) {
                    $item->image = '<img src="' . JURI::base(true) . '/images/stories/' . $menu_image . '" alt="' . $item->name . '" align="right"'.$imagerollover.' />';
                } else {
                    $item->image = '<img src="' . JURI::base(true) . '/images/stories/' . $menu_image . '" alt="' . $item->name . '" align="left"'.$imagerollover.' />';
                }
            }
            // if only image, then no title
            if (preg_match('/\[img\]/', $item->name)) {
                $item->image = '<img src="' . JURI::base(true) . '/images/stories/' . $menu_image . '" border="0" alt="' . $item->name . '" align="left"'.$imagerollover.' />';
                $titreCK = 'img';
                $item->img = true;
            }

            // manage plugin parameters, need the plugin maximenu_ck_params to be installed and active
            $item->description = $menu_params->def('maximenu_desc', '');
            $item->insertmodule = $menu_params->def('maximenu_insertmodule', 0);
            $item->module = $menu_params->def('maximenu_module', '');
            $item->createcolumn = $menu_params->def('maximenu_createcolumn', 0);
            $item->showcoltitle = $menu_params->def('maximenu_showcoltitle', 0);
            $item->colwidth = $menu_params->def('maximenu_colwidth', '');
            $item->leftmargin = $menu_params->def('maximenu_leftmargin', '');
            $item->topmargin = $menu_params->def('maximenu_topmargin', '');
            $item->imgonly = $menu_params->def('maximenu_imgonly', 0);
            $item->relattr = $menu_params->def('maximenu_relattr', '');
            $item->titleattr = $menu_params->def('maximenu_titleattr', '');
            $item->css = $menu_params->def('maximenu_css', '');
            $item->liclass = $menu_params->def('maximenu_liclass', '');
            $item->colbgcolor = $menu_params->def('maximenu_colbgcolor', '');


            // add styles to the page for customization
            $itemstyles = "";
            if ($item->titlecolor = $menu_params->def('maximenu_titlecolor', ''))
                $itemstyles .= "div#" . $menuID . " ul.maximenuCK li.item" . $item->id . " > a span.titreCK {color:" . $item->titlecolor . " !important;} div#" . $menuID . " ul.maximenuCK li.item" . $item->id . " > span.separator span.titreCK {color:" . $item->titlecolor . " !important;}";

            if ($item->desccolor = $menu_params->def('maximenu_desccolor', ''))
                $itemstyles .= "div#" . $menuID . " ul.maximenuCK li.item" . $item->id . " > a span.descCK {color:" . $item->desccolor . " !important;} div#" . $menuID . " ul.maximenuCK li.item" . $item->id . " > span.separator span.descCK {color:" . $item->desccolor . " !important;}";

            if ($item->titlehovercolor = $menu_params->def('maximenu_titlehovercolor', ''))
                $itemstyles .= "div#" . $menuID . " ul.maximenuCK li.item" . $item->id . " > a:hover span.titreCK {color:" . $item->titlehovercolor . " !important;} div#" . $menuID . " ul.maximenuCK li.item" . $item->id . " > span.separator:hover span.titreCK {color:" . $item->titlehovercolor . " !important;}";

            if ($item->deschovercolor = $menu_params->def('maximenu_deschovercolor', ''))
                $itemstyles .= "div#" . $menuID . " ul.maximenuCK li.item" . $item->id . " > a:hover span.descCK {color:" . $item->deschovercolor . " !important;} div#" . $menuID . " ul.maximenuCK li.item" . $item->id . " > span.separator:hover span.descCK {color:" . $item->deschovercolor . " !important;}";

            if ($item->titleactivecolor = $menu_params->def('maximenu_titleactivecolor', ''))
                $itemstyles .= "div#" . $menuID . " ul.maximenuCK li.active.item" . $item->id . " > a span.titreCK {color:" . $item->titleactivecolor . " !important;} div#" . $menuID . " ul.maximenuCK li.active.item" . $item->id . " > span.separator span.titreCK {color:" . $item->titleactivecolor . " !important;}";

            if ($item->descactivecolor = $menu_params->def('maximenu_descactivecolor', ''))
                $itemstyles .= "div#" . $menuID . " ul.maximenuCK li.active.item" . $item->id . " > a span.descCK {color:" . $item->descactivecolor . " !important;} div#" . $menuID . " ul.maximenuCK li.active.item" . $item->id . " > span.separator span.descCK {color:" . $item->descactivecolor . " !important;}";

            if ($item->libgcolor = $menu_params->def('maximenu_libgcolor', ''))
                $itemstyles .= "div#" . $menuID . " ul.maximenuCK li.item" . $item->id . " {background:" . $item->libgcolor . " !important;}";

            if ($item->lihoverbgcolor = $menu_params->def('maximenu_lihoverbgcolor', ''))
                $itemstyles .= "div#" . $menuID . " ul.maximenuCK li.item" . $item->id . ":hover {background:" . $item->lihoverbgcolor . " !important;}";


            if ($itemstyles) $document->addStyleDeclaration($itemstyles);


            if ($item->description) {
                $item->desc = '<span class="descCK">' . $item->description . '</span>';
                //$item->desc = $item->description;
            }

            if ($item->insertmodule) {
                $item->content = '<div class="maximenuCK_mod">' . modmaximenu_CKHelper::GenModuleById($item->module, $params, $modulesList) . '<div class="clr"></div></div>';
            }

            $item->name = $titreCK;

            if ($item->imgonly) {
                $item->image = '<img src="' . JURI::base(true) . '/images/stories/' . $menu_image . '" border="0" alt="' . $item->name . '" align="left"'.$imagerollover.' />';
                $titreCK = 'img';
                $item->img = true;
            }

            if ($item->relattr) {
                $item->rel = ' rel="' . $item->relattr . '"';
            }

            $item->anchor_css = '';
            if ($item->css) {
                $item->anchor_css = $item->css;
            }

            $item->anchor_title = '';
            if ($item->titleattr) {
                $item->anchor_title = ' title="' . $item->titleattr . '"';
            }

            if ($item->createcolumn) {
                if (isset($items[$i - 1]) && $items[$i - 1]->deeper) {
                    $item->onlycoltitle = true;
                }

                $item->colonne = true;
                $item->coltitle = '';
                if ($item->showcoltitle)
                    $item->coltitle = '<h2>' . $item->name . '</h2>';
                $item->name = '';
            }



            if ($item->createcolumn && $item->colwidth) {
                $parentItem = modmaximenu_CKHelper::getParentItem($item->parent, $items);
                if (isset($parentItem->submenuswidth)) {
                    $parentItem->submenuswidth = strval($parentItem->submenuswidth) + strval($item->colwidth) + 10;
                } else {
                    $parentItem->submenuswidth = strval($item->colwidth) + 10;
                }
                if (isset($items[$i - 1]) && $items[$i - 1]->deeper) {
                    $items[$i - 1]->colwidth = ' style="width:' . $item->colwidth . 'px;"';
                } else {
                    $item->colwidth = ' style="width:' . $item->colwidth . 'px;"';
                }
            }
        }



        return $items;
    }

    function getParentItem($id, $items) {
        foreach ($items as $item) {
            if ($item->id == $id)
                return $item;
        }
    }

    function GenModuleByName($paramsCK, &$params) {
        $forcetitle = $params->get('forcetitle', '0');
        if ($forcetitle == 1) {
            $attribs['style'] = 'xhtml';
        } else {
            $attribs['style'] = 'none';
        }
        // get the name of the module to load
        $paramsCK = preg_replace('/modname=/', '', $paramsCK);
        // load the module
        if (JModuleHelper::isEnabled($paramsCK)) {
            $module = JModuleHelper::getModule($paramsCK);
            //$attribs['style'] = 'none';
            return JModuleHelper::renderModule($module, $attribs);
        }
    }

    function GenModuleById($paramsCK, &$params, &$modulesList) {
        $forcetitle = $params->get('forcetitle', '0');
        if ($forcetitle == 1) {
            $attribs['style'] = 'xhtml';
        } else {
            $attribs['style'] = 'none';
        }
        // get the name of the module to load
        $paramsCK = preg_replace('/modid=/', '', $paramsCK);
        $modtitle = $modulesList[$paramsCK]->title;
        $modname = $modulesList[$paramsCK]->module;
        $modname = preg_replace('/mod_/', '', $modname);

        // load the module
        if (JModuleHelper::isEnabled($modname)) {
            $module = JModuleHelper::getModule($modname, $modtitle);
            return JModuleHelper::renderModule($module, $attribs);
        }
    }

    function CreateModulesList() {
        $db = & JFactory::getDBO();
        $query = "
			SELECT *
			FROM #__modules
			WHERE published=1
			ORDER BY id
			;";
        $db->setQuery($query);
        $modulesList = $db->loadObjectList('id');
        return $modulesList;
    }

}

?>