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

$GLOBALS['issues_templates'] = array();

$GLOBALS['issues_templates']['lifters'] = array(
    // common prefix to alle newly created pages
    // must be a WikiWord and should be unique to
    // avoid conflicts with other templates
    'prefix' => 'LifTers',
	// additional markup for a lifter
	'additional_description' => "(:liftersprogress:)"
);

$GLOBALS['issues_templates']['step'] = array(
    // common prefix to alle newly created pages
    // must be a WikiWord and should be unique to
    // avoid conflicts with other templates
    'prefix' => 'StEP'
);


$GLOBALS['issues_templates']['biest'] = array(
    // common prefix to alle newly created pages
    // must be a WikiWord and should be unique to
    // avoid conflicts with other templates
    "prefix"=>"BIEST",
    // list of fields to parse for list view, matching is case-insensitive
    // order must be same as indicated by listheader
    // first field (name) will be added
    "listview"=>array('erstellt','autor','zustšndig','version','status','zusammenfassung'),
    // standard order of fields for sort function
    //"stdorder"=>'-erstellt,status,autor,zustšndig,version,beschreibung',
    // header for list tables, first column always is the pages name
    // order defines order criterion for sort action
    "listheader" => array(
        array("order"=>"-name","heading"=>"BIEST#"),
        array("order"=>"erstellt", "heading"=>"Erstellt"),
        array("order"=>"autor", "heading"=>"Autor"),
        array("order"=>"zustšndig", "heading"=>"Zustšndig"),
        array("order"=>"version", "heading"=>"Version"),
        array("order"=>"status", "heading"=>"Status"),
        array("order"=>"zusammenfassung", "heading"=>"Zusammenfassung")
    ),
    'defaultstatus' => "offen"
);