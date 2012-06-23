<?php

/*
 *  Copyright (c) 2012  Rasmus Fuhse <fuhse@data-quest.de>
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
    // list of fields to parse for list view, matching is case-insensitive
    // order must be same as indicated by listheader
    // first field (name) will be added
    "listview"=>array('erstellt','autor','zuständig','version','komplexität','status','zusammenfassung'),
    // standard order of fields for sort function
    "stdorder"=>'-erstellt,status,version,autor,zuständig,zusammenfassung',
    // header for list tables, first column always is the pages name
    // order defines order criterion for sort action
    "listheader"=>array(
        array("order"=>"-name","heading"=>"Lifters#"),
        array("order"=>"erstellt", "heading"=>"Erstellt"),
        array("order"=>"autor", "heading"=>"Autor"),
        array("order"=>"zuständig", "heading"=>"Zuständig"),
        array("order"=>"version", "heading"=>"Version"),
        array("order"=>"komplexität", "heading"=>"Komplex."),
        array("order"=>"status", "heading"=>"Status"),
        array("order"=>"zusammenfassung", "heading"=>"Zusammenfassung")
    ),
    'defaultstatus' => "neu"
);

$GLOBALS['issues_templates']['step'] = array(
    // common prefix to alle newly created pages
    // must be a WikiWord and should be unique to
    // avoid conflicts with other templates
    'prefix' => 'StEP',
    // list of fields to parse for list view, matching is case-insensitive
    // order must be same as indicated by listheader
    // first field (name) will be added
    "listview"=>array('erstellt','autor','zuständig','version','komplexität','status','zusammenfassung'),
    // standard order of fields for sort function
    "stdorder"=>'-erstellt,status,version,autor,zuständig,zusammenfassung',
    // header for list tables, first column always is the pages name
    // order defines order criterion for sort action
    "listheader"=>array(
        array("order"=>"-name","heading"=>"StEP#"),
        array("order"=>"erstellt", "heading"=>"Erstellt"),
        array("order"=>"autor", "heading"=>"Autor"),
        array("order"=>"zuständig", "heading"=>"Zuständig"),
        array("order"=>"version", "heading"=>"Version"),
        array("order"=>"komplexität", "heading"=>"Komplex."),
        array("order"=>"status", "heading"=>"Status"),
        array("order"=>"zusammenfassung", "heading"=>"Zusammenfassung")
    ),
    'defaultstatus' => "neu"
);


$GLOBALS['issues_templates']['biest'] = array(
    // common prefix to alle newly created pages
    // must be a WikiWord and should be unique to
    // avoid conflicts with other templates
    "prefix"=>"BIEST",
    // list of fields to parse for list view, matching is case-insensitive
    // order must be same as indicated by listheader
    // first field (name) will be added
    "listview"=>array('erstellt','autor','zuständig','version','status','zusammenfassung'),
    // standard order of fields for sort function
    "stdorder"=>'-erstellt,status,autor,zuständig,version,beschreibung',
    // header for list tables, first column always is the pages name
    // order defines order criterion for sort action
    "listheader" => array(
        array("order"=>"-name","heading"=>"BIEST#"),
        array("order"=>"erstellt", "heading"=>"Erstellt"),
        array("order"=>"autor", "heading"=>"Autor"),
        array("order"=>"zuständig", "heading"=>"Zuständig"),
        array("order"=>"version", "heading"=>"Version"),
        array("order"=>"status", "heading"=>"Status"),
        array("order"=>"zusammenfassung", "heading"=>"Zusammenfassung")
    ),
    'defaultstatus' => "offen"
);