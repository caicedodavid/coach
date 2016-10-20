<div>
    <table class="vertical-table">
    <?= $this->element('Sessions/general_view', ["session" => $session]);?>
    </table>
</div>
<br>
<br>
<?= $this->element('Sessions/cancel_session_button', ['session' => $session, 'button' => 'Cancel Request', 'action' => 'pending','message' => 'Are you sure you want to cancel this requested session?']);?>