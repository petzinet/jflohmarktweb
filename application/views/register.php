<?php if (validation_errors()): ?>
    <div class="alert alert-success" role="alert">
        <a href="#" class="alert-link"><?php echo validation_errors(); ?></a>
    </div>
<?php endif; ?>
<?php echo form_open('register'); ?>
<h1>Registrierung</h1>
<p>Nachfolgend kannst Du Dich als Verkäufer registrieren:</p>
<div class="form-group">
    <label for="givenName">Vorname</label>
    <input type="text" class="form-control" value="<?php echo set_value('givenName'); ?>" name="givenName" id="givenName">
</div>
<div class="form-group">
    <label for="name">Nachname</label>
    <input type="text" class="form-control" value="<?php echo set_value('name'); ?>" name="name" id="name">
</div>
<div class="form-group">
    <label for="addressAppendix">Adresszusatz</label>
    <input type="text" class="form-control" value="<?php echo set_value('addressAppendix'); ?>" name="addressAppendix" id="addressAppendix">
</div>
<div class="form-group">
    <label for="streeet">Strasse und Hausnummer</label>
    <input type="text" class="form-control" value="<?php echo set_value('street'); ?>" name="street" id="street">
</div>
<div class="form-group">
    <label for="zipCode">Postleitzahl</label>
    <input type="text" class="form-control" value="<?php echo set_value('zipCode'); ?>" name="zipCode" id="zipCode">
</div>
<div class="form-group">
    <label for="city">Ort</label>
    <input type="text" class="form-control" value="<?php echo set_value('city'); ?>" name="city" id="city">
</div>
<div class="form-group">
    <label for="phone">Telefon</label>
    <input type="text" class="form-control" value="<?php echo set_value('phone'); ?>" name="phone" id="phone">
</div>
<div class="form-group">
    <label for="email">Email</label>
    <input type="text" class="form-control" value="<?php echo set_value('email'); ?>" name="email" id="email">
</div>
<div class="form-group">
    <label for="email2">Email (Wiederholung)</label>
    <input type="text" class="form-control" value="<?php echo set_value('email2'); ?>" name="email2" id="email2">
</div>
<h3>Kontoverbindung</h3>
<p>Durch die nachfolgende Angabe einer Kontoverbindung kann der Verkaufserlös auf dieses Konto überwiesen werden. So wird das aufwändige Zählen von Bargeld vermieden und der damit verbundene Aufwand bleibt uns allen erspart.<br>
Soll der Verkaufserlös bei der Abholung der Ware in Bar ausgezahlt werden, kann die Bankverbindung auch leer bleiben.</p>
<div class="form-group">
    <label for="accountHolder">Kontoinhaber</label>
    <input type="text" class="form-control" value="<?php echo set_value('accountHolder'); ?>" name="accountHolder" id="accountHolder">
</div>
<div class="form-group">
    <label for="iban">IBAN</label>
    <input type="text" class="form-control" value="<?php echo set_value('iban'); ?>" name="iban" id="iban">
</div>
<div class="form-group">
    <label for="bic">BIC</label>
    <input type="text" class="form-control" value="<?php echo set_value('bic'); ?>" name="bic" id="bic">
</div>
<?php if($question !== FALSE):?>
<h3>Sicherheitsfrage</h3>
<p>Bitte gib die Antwort der folgenden Frage ein, um das Formular abschicken zu können. Dies verhindert den Missbrauch durch automatische Internetanwendungen.</p>
<div class="form-group">
    <label for=answer"><?php echo strip_tags($question[0]->name);?></label>
    <input type="text" class="form-control" value="<?php echo set_value('answer'); ?>" name="answer" id="answer">
    <input type="hidden" name="question" value="<?php echo strip_tags($question[0]->id);?>">
</div>
<?php endif;?>
<button type="submit" class="btn btn-default">
    <span class="glyphicon glyphicon-ok"></span> Absenden
</button>
<?php echo form_close(); ?>