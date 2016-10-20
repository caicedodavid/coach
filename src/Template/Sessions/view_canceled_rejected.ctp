<div>
    <table class="vertical-table">
    <?= $this->element('Sessions/general_view', ["session" => $session]);?>
    </table>
</div>
<br>
<br>
<?= "This session was " . $statusArray[$session->status]?>