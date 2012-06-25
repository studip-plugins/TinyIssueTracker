<h2><?= sprintf(_("Neuer %s-Eintrag"), $name) ?></h2>
<?= _("Name des Autoren und Erstellungszeit werden automatisch hinzugefügt.") ?>
<form action="<?= URLHelper::getLink('?') ?>" method=post>
    <input type="hidden" name="issue_action" value="new_<?= htmlReady($type) ?>">
    <input type="hidden" name="keyword" value="<?= htmlReady($keyword) ?>">
<table>
    <tr>
        <td>Zusammenfassung:</td>
        <td><input size=60 name="zusammenfassung"></td>
    </tr>
    <tr>
        <td>Zuständig:</td>
        <td><input size=60 name="zustaendig"></td>
    </tr>
    <? if (in_array("version", $attributes['listview'])) : ?>
    <tr>
        <td>Version:</td>
        <td>
            <select size=0 name="software_version">
                <option value="2.3">2.3 (April 2012)</option>
                <option value="2.4">2.4 (Oktober 2012)</option>
                <option>unbestimmt</option>
            </select>
        </td>
    </tr>
    <? endif ?>
    <? if (in_array("komplexität", $attributes['listview'])) : ?>
    <tr>
        <td>Komplexität:</td>
        <td>
            <select name="komplexitaet">
                <option>gering</option>
                <option>mittel</option>
                <option>hoch</option>
            </select>
        </td>
    </tr>
    <? endif ?>
    <tr>
        <td>Beschreibung:</td>
        <td><textarea name="beschreibung" cols="60" rows="10"></textarea></td>
    </tr>
    <tr>
        <td>Foren-Thema erzeugen:</td>
        <td><input type="checkbox" name="create_topic" value="1" checked></td>
    </tr>

    <tr>
        <td>&nbsp;</td><td><?= Studip\Button::create(_('Eintragen')) ?></td>
    </tr>
</table>
<?= CSRFProtection::tokenTag() ?>
</form>