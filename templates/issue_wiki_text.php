!!!!<?= $pagename ?><?= "\n" ?>
Zusammenfassung: <?= Request::get("zusammenfassung") ?><?= "\n" ?>
Autor: <? if (keywordExists(get_fullname(), $_SESSION['SessionSeminar'])) : ?>
[[<?= get_fullname() ?>]]
<? else : ?>
[<?= get_fullname() ?>]<?= $GLOBALS['ABSOLUTE_URI_STUDIP']."/about.php?username=". get_username() ?>
<? endif ?><?= "\n" ?>
<? if (Request::get("version")) : ?>
Version: <?= Request::get("version") ?><?= "\n" ?>
<? endif ?>
Zust�ndig: <?= Request::get("zustaendig") ?><?= "\n" ?>
<? if (Request::get("komplexitaet")) : ?>
Komplexit�t: <?= Request::get("komplexitaet") ?><?= "\n" ?>
<? endif ?>
Erstellt: <?= date('Y-m-d H:i',time()) ?><?= "\n" ?>
Status: <?= $status ?><?= "\n" ?>
Beschreibung:<?= "\n" ?>
<?= "\n" ?>
<?= Request::get("beschreibung") ?>