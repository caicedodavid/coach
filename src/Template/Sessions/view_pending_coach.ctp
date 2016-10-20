<?= $this->element('Sessions/accept_decline_buttons', ['session' => $session]);?>
<br>
<br>
<div>
    <table class="vertical-table">
    <?= $this->element('Sessions/general_view', ["session" => $session]);?>
    </table>
</div>

