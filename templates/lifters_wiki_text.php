!!!!<?= $pagename ?><?= "\n" ?>
Zusammenfassung: <?= Request::get("zusammenfassung") ?><?= "\n" ?>
Autor: <?= get_fullname(NULL, 'no_title_short') ?><?= "\n" ?>
Version: <?= Request::get("software_version") ?><?= "\n" ?>
Zust�ndig: <?= Request::get("zustaendig") ?><?= "\n" ?>
Komplexit�t: <?= Request::get("komplexitaet") ?><?= "\n" ?>
Erstellt: <?= date('Y-m-d H:i',time()) ?><?= "\n" ?>
Status: <?= $status ?><?= "\n" ?>
Beschreibung:<?= "\n" ?>
<?= "\n" ?>
<?= Request::get("beschreibung") ?><?= "\n" ?>
<?= "\n" ?>
(:liftersprogress:)