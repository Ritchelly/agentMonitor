<?php
include('classes/amiclass.php');

if(isset($_GET['AgentsStatus'])) {
   $ActionId='123';
   echo AgentsStatus($ActionId);
}

if(isset($_GET['ChannelsConcise'])) {
    echo( ChannelsConcise() );
}

//----------------------------------------------------------------------------------------------------------------------
function AgentsStatus($ActionID) {
    $oAgentsStatus= new Agent();
    $AgentsStatus= $oAgentsStatus->AgentsStatus($ActionID);
    return($AgentsStatus);
}

function ChannelsConcise() {
    $oChannelsConcise= new ChannelsConcise();
    $ChannelsConcise=$oChannelsConcise->CaptureAllChannels();
    return ($ChannelsConcise);
}

?>