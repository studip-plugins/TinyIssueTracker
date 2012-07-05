<?php

/*
 *  Copyright 2004-2009 Patrick R. Michaud (pmichaud@pobox.com)
 *  Copyright 2004-2009 Tobias Thelen <tobias.thelen@uos.de>
 *  Copyright (c) 2012  Rasmus Fuhse <fuhse@data-quest.de>
 * 
 *  This file was part of PITS (PmWiki Issue Tracking System).
 * 
 *  This program is free software; you can redistribute it and/or
 *  modify it under the terms of the GNU General Public License as
 *  published by the Free Software Foundation; either version 2 of
 *  the License, or (at your option) any later version.
 */

require_once 'lib/forum.inc.php';
require_once 'lib/wiki.inc.php';
require_once dirname(__file__).'/old_templates.php';

class TinyIssueTracker extends StudIPPlugin implements SystemPlugin {
    
    public function __construct() {
        parent::__construct();
        
        if (Request::get('issue_action')) {
            $type = str_replace("new_", "", trim(Request::get('issue_action')));
            self::wiki_newissue($type);
        }

        $types = array_keys($GLOBALS['issues_templates']);
        foreach ($types as $type) {
            WikiFormat::addWikiMarkup($type."list", '\\(:('.$type.')list\\s*(.*?):\\)', "", "TinyIssueTracker::issuelist_markup");
            WikiFormat::addWikiMarkup("create".$type, '\\(:('.$type.')form:\\)', "", "TinyIssueTracker::createissue_markup");
        }

        WikiFormat::addWikiMarkup("liftersprogress", '\\(:liftersprogress\\s*(.*?):\\)', "", "TinyIssueTracker::liftersprogress_markup");
    }

    public static function createissue_markup($markup, $matches) {
        $type = $matches[1];
        $attributes = self::getAttributes($type);
        $template = self::getTemplate("issueform.php", null);
        $template->set_attribute('keyword', Request::get("keyword") ? Request::get("keyword") : "WikiWikiWeb");
        $template->set_attribute('name', $attributes['prefix']);
        $template->set_attribute('attributes', $attributes);
        $template->set_attribute('type', $type);
        return str_replace("\n", "", $template->render());
    }

    public static function issuelist_markup($markup, $matches, $content) {
        PageLayout::addScript("jquery.tablesorter.min.js");
        $type = $matches[1];
        $option = $matches[2];
        $keyword = Request::get("keyword") ? Request::get("keyword") : "WikiWikiWeb";
        $template = self::getAttributes($type);
        if (!$template) {
            return $markup->format($matches[0]);
        }
        $opt = array('q' => $option);
        $opt = array_merge($opt,(array)$_REQUEST);
        $list = self::getissuepagelist($type); //<a href='".URLHelper::getLink("?keyword=$keyword&order=".urlencode($h['order']))."'>
        $out[] = "<table border='1' cellspacing='0' cellpadding='3' class='issuelist'><thead></tr>";
        foreach ($template['listheader'] as $h) {
            $out[]="<th>{$h['heading']}</th>";
        }
        $out[]="</tr></thead>\n";
        $terms = preg_split('/((?<!\\S)[-+]?[\'"].*?[\'"](?!\\S)|\\S+)/',
            $opt['q'],-1,PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);
        foreach($terms as $t) {
            if (trim($t)=='') continue;
            if (preg_match('/([^\'":=]*)[:=]([\'"]?)(.*?)\\2$/',$t,$match))
                $opt[strtolower($match[1])] = $match[3];
        }
        $n=0; $slist=array();
        foreach($list as $s) {
            $page = getLatestVersion($s,$_SESSION['SessionSeminar']);
            preg_match_all("/(^|\n)([A-Za-z][^:]*):([^\n]*)/",$page['body'],$match);
            $fields = array();
            for($i=0;$i<count($match[2]);$i++)
                $fields[strtolower($match[2][$i])] = htmlentities($match[3][$i],ENT_QUOTES);
            foreach(explode(',',$template['stdorder']) as $h) {
                $h_html = htmlentities($h);
                if (!@$opt[$h_html]) continue;
                foreach(preg_split('/[ ,]/',$opt[$h_html]) as $t) {
                    if (substr($t,0,1)!='-' && substr($t,0,1)!='!') {
                        if (strpos(strtolower(@$fields[$h]),strtolower($t)) === false)
                            continue 3;
                    } else if (strpos(strtolower(@$fields[$h]), strtolower(substr($t,1)))!==false)
                        continue 3;
                }
            }
            $slist[$n] = $fields;
            $slist[$n]['name'] = $s;
            $n++;
        }
        $cmp = self::createOrderFunction(@$opt['order'].",".$template['stdorder']);
        usort($slist,$cmp);
        $out[] = "<tbody>";
        foreach($slist as $s) {
            $out[] = "<tr><td><font size=-1><a href='".URLHelper::getLink("?keyword=$s[name]")."'>$s[name]</a></font></td>";
            foreach($template['listview'] as $h)
                $out[] = @"<td><font size=-1>".$markup->format($s[$h])."&nbsp;</font></td>";
                $out[] = "</tr>";
        }
        $out[] = "</tbody>";
        $out[] = "</table>";
        $out[] = '<script>jQuery(function () {jQuery("table.issuelist").tablesorter(); });</script>';
        return str_replace("\n", "", implode('',$out));
    }


    protected function wiki_newissue($type) {
        $template = self::getAttributes($type);
        extract($_POST,EXTR_SKIP); // locally set post-vars for template
        foreach (self::getissuepagelist($type) as $l) {
            $issue = max(@$issue, substr($l, strlen($template['prefix'])));
        }
        $pagename = sprintf("%s%05d", $template['prefix'], @$issue+1);
        $user_id = $GLOBALS['user']->id;
	$wiki_text_template = self::getTemplate(file_exists(dirname(__file__)."/templates/".$type."_wiki_text.php") ? $type."_wiki_text.php" : "issue_wiki_text.php", null);
        $wiki_text_template->set_attribute("pagename", $pagename);
        $wiki_text_template->set_attribute("status", $template['defaultstatus']);
        $wiki_text_template->set_attribute("additional_description", $template['additional_description']);
        $wiki_text = $wiki_text_template->render();

        if (Request::get("create_topic")) {
            $forum_text = sprintf(_("Die aktuellste Fassung dieses %s finden Sie immer im %sWiki%s"),$template['prefix'], '[',']'.URLHelper::getURL($GLOBALS['ABSOLUTE_URI_STUDIP'].'wiki.php?keyword='.$pagename)) . " \n--\n". Request::get("beschreibung");
            if($tt = CreateTopic($pagename . ': ' . Request::get("zusammenfassung"), get_fullname($user_id), $forum_text, 0, 0, $_SESSION['SessionSeminar'], $user_id)) {
                $message = MessageBox::success(_('Ein neues Thema im Forum wurde angelegt.'));
                PageLayout::postMessage($message);
                $wiki_text = '['._("Link zum Forumsbeitrag").']' . URLHelper::getURL($GLOBALS['ABSOLUTE_URI_STUDIP'] . 'forum.php?open=' . $tt . '#anker') . " \n--\n" . $wiki_text;
            }
        }

        $query = "INSERT INTO wiki (range_id, keyword, body, user_id, chdate, version)"
            . "VALUES (?, ?, ?, ?, UNIX_TIMESTAMP(), '1')";
        DBManager::get()
            ->prepare($query)
            ->execute(array($_SESSION['SessionSeminar'], $pagename, $wiki_text, $user_id));

        $message = MessageBox::success(sprintf(_('Ein neuer Eintrag wurde angelegt. Sie können ihn nun weiter bearbeiten oder %szurück zur Ausgangsseite%s gehen.'),'<a href="'.URLHelper::getLink('?', array('keyword' => Request::get("keyxword"))).'">','</a>'));
        PageLayout::postMessage($message);
        //redirect:
        Request::set('keyword', $pagename);
        $GLOBALS['keyword'] = $pagename;
        Request::set('view', "show");
        $GLOBALS['view'] = "show";
    }


    public static function liftersprogress_markup($markup, $matches) {
        $template = self::getAttributes("lifters");
        $keyword = Request::get("keyword") ? Request::get("keyword") : "WikiWikiWeb";
        
        $lnr = $matches[1];
        # retrieve ID of lifters from keyword of wiki page
        if (!$lnr && Request::get('keyword')) {
            $lnr = trim(str_replace($template['prefix'], "", $keyword));
        }
        $id = (int) $lnr;

        if (!$id) {
            return '';
        }

        $cache_key = "wiki/liftersprogress/" . $id;
        $cache = StudipCacheFactory::getCache();
        $result = $cache->read($cache_key);

        if ($result === FALSE) {
            $command = sprintf('cd %s ; tools/lifter/lifter-status -l%d',
                $GLOBALS['STUDIP_BASE_PATH'], $id);
            exec($command, $output, $return_var);

            $out = array();
            if (!$return_var) {
                $out[] = '<h1>'._("Status von Lifters") . sprintf('%03d', $id) . '</h1>';
                $out[] = '<p>' . strftime("%x %X", time()) . '</p>';

                if (count($output)) {
                    $out[] =  '<h3>' . htmlReady(array_pop($output)) .'</h3>';
                    $out[] = '<pre>';
                    foreach($output as $line) {
                        $out[] = htmlReady($line);
                    }
                    $out[] = '</pre>';
                }
            }

            $result = implode("\n", $out);
            $cache->write($cache_key, $result, 300);
        }
        return $result;
    }

    protected function createOrderFunction($order) {
        $code = '';
        foreach(preg_split('/[\\s,|]+/',strtolower($order),-1,PREG_SPLIT_NO_EMPTY) 
            as $o) {
            if (substr($o,0,1)=='-') { $r='-'; $o=substr($o,1); }
            else $r='';
            if (preg_match('/\\W/',$o)) continue;
            $code .= "\$c=strcasecmp(@\$x['$o'],@\$y['$o']); if (\$c!=0) return $r\$c;\n";
        }
        $code .= "return 0;\n";
        return create_function('$x,$y',$code);
    }
    
    protected static function getissuepagelist($type) {
        $template = self::getAttributes($type);
        $query = "SELECT DISTINCT keyword FROM wiki WHERE range_id = ? AND keyword LIKE CONCAT(?, '%')";
        $statement = DBManager::get()->prepare($query);
        $statement->execute(array(
            $_SESSION['SessionSeminar'], 
            $template['prefix']
        ));
        $list = $statement->fetchAll(PDO::FETCH_COLUMN);
        return $list;
    }
    
    protected static function getAttributes($type) {
        $attributes = $GLOBALS['issues_templates'][$type];
        $default_attributes = self::getDefaultIssueAttributes();
        $default_attributes['listheader'][0]['heading'] = $attributes['prefix']."#";
        return array_merge($default_attributes, $attributes);
    }
    
    private static function getDefaultIssueAttributes() {
        return array(
            // list of fields to parse for list view, matching is case-insensitive
            // order must be same as indicated by listheader
            // first field (name) will be added
            "listview"=>array('erstellt','autor','zuständig','version','komplexität','status','zusammenfassung'),
            // standard order of fields for sort function
            "stdorder"=>'-erstellt,status,version,autor,zuständig,zusammenfassung',
            // header for list tables, first column always is the pages name
            // order defines order criterion for sort action
            "listheader"=>array(
                array("order"=>"-name","heading"=>"Issue#"),
                array("order"=>"erstellt", "heading"=>"Erstellt"),
                array("order"=>"autor", "heading"=>"Autor"),
                array("order"=>"zuständig", "heading"=>"Zuständig"),
                array("order"=>"version", "heading"=>"Version"),
                array("order"=>"komplexität", "heading"=>"Komplex."),
                array("order"=>"status", "heading"=>"Status"),
                array("order"=>"zusammenfassung", "heading"=>"Zusammenfassung")
            ),
            //status of a new issue:
            'defaultstatus' => "neu",
			'additional_description' => ""
        );
    }
    
    
    protected static $template_factory = null;

    protected static function getTemplate($template_file_name, $layout = "without_infobox") {
        if ($layout) {
            PageLayout::setTitle(__class__);
        }
        if (!self::$template_factory) {
            self::$template_factory = new Flexi_TemplateFactory(dirname(__file__)."/templates");
        }
        $template = self::$template_factory->open($template_file_name);
        if ($layout) {
            $template->set_layout($GLOBALS['template_factory']->open($layout === "without_infobox" ? 'layouts/base_without_infobox' : 'layouts/base'));
        }
        return $template;
    }
    
}