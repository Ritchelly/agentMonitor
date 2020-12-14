<?php
 define("ASTERISK_VERSION", 16);

class AMI {

    public function ConectaAmi() {
        (!isSet($_SESSION)?session_start():'');


        $server='localhost';
        $username='monitormanager';
        $secret='manager_password';
        $port= '5038';

        $this->socket=@fsockopen($server,$port , $errno, $errstr, 1);

        if(($errno!=0) || ($errstr!="") ) {
            echo  'Erro codigo : '.$errno .'<br>'. $errstr ;
            return '0';
        }
        else {
            fputs($this->socket, "Action: Login\r\n");
            fputs($this->socket, "UserName:$username\r\n");
            fputs($this->socket, "Secret: $secret\r\n\r\n");
            return '1';
        }
    }
}

class Agent extends AMI{

    function AgentsStatus($ActionID)
    {
        if($this->ConectaAmi())
        {
            fputs($this->socket, "Action: Agents\r\n" );
            fputs($this->socket, "ActionID: $ActionID\r\n\r\n" );
            fputs($this->socket, "Action: Logoff\r\n\r\n");

            $Buffer=[];
            $i=0;
            while (!feof($this->socket)) {

                $wrets[$i]=strtolower(trim(fgets($this->socket, 128)));

                $a=strpos($wrets[$i],':');
                if($a)
                {
                    $Linha=explode(':',$wrets[$i]);

                    $Key=trim(@$Linha[0]);
                    $Val=trim(@$Linha[1]);

                    if($Key=='event' & $Val=='agents')
                    {
                        $wrets[$i]=strtolower(trim(fgets($this->socket, 128)));
                        $Linha=explode(':',$wrets[$i]);

                        $Key=trim(@$Linha[0]);
                        $Val=trim(@$Linha[1]);
                        $agent=$Val;
                        $Buffer[$Val]=array($Key=>$Val);

                        while($Key!='actionid')
                        {
                            $wrets[$i]=strtolower(trim(fgets($this->socket, 128)));
                            $Linha=explode(':',$wrets[$i]);
                            $Key=trim(@$Linha[0]);
                            $Val=trim(@$Linha[1]);
                            $Buffer[$agent][$Key]=$Val;
                        }
                    }
                    $i++;
                }
            }

           $Buffer['Total']['QtdAgentes']=count($Buffer);

           return json_encode($Buffer, JSON_PRETTY_PRINT);

        }

    }
}
/**
 * 
 * Channel:Context:Exten:Priority:Stats:Application:Data:CallerID:Accountcode:Amaflags:Duration:Bridged
 */
class ChannelsConcise extends AMI{

    function CaptureAllChannels()
    {


        if($this->ConectaAmi())
        {
           fputs($this->socket, "Action: Command\r\n" );
            fputs($this->socket, "command:  core show channels concise\r\n\r\n" );
            fputs($this->socket, "Action: Logoff\r\n\r\n");

            $i=0;
            while (!feof($this->socket)) {
                $wrets[$i]=strtolower(trim(fgets($this->socket, 2048)));
                $i++;
            }
           
            if ( ASTERISK_VERSION == 13 ) {
                /**
                    Asterisk Call Manager/2.9.0
                    Message: Authentication accepted
                    Event: FullyBooted
                    Status: Fully Booted
                    Event: SuccessfulAuth
                    EventTV: 2020-10-29T12:57:29.644-0300
                    Service: AMI
                    AccountID: system
                    LocalAddress: IPV4/TCP/0.0.0.0/8190
                    UsingPassword: 0

                    Privilege: Command
                    SIP/1102-0000021c!interno!33988151213!4!Ring!Dial!SIP/telenova/033988151213,,Tt!1102!!!3!14!!1603987035.833

                    Message: Thanks for all the fish.
                 */

                $KeyStart=array_search('privilege: command', $wrets);
                $KeyEnd=array_search('--end command--', $wrets);
                $Buffer=[];
                for($i=$KeyStart+1;$i<$KeyEnd;$i++) {
                    $Key[]=explode('!',$wrets[$i]);

                    for($x=0;$x<count($Key);$x++) {
                        $Buffer[@$Key[$x][0]]=@$Key[$x];
                    }
                }
            }

            if ( ASTERISK_VERSION == 16 ) {
                 /**
                [0] => asterisk call manager/5.0.2
                [1] => response: success
                [2] => message: authentication accepted
                [3] => 
                [4] => event: fullybooted
                [5] => privilege: system,all
                [6] => uptime: 1103334
                [7] => lastreload: 6518
                [8] => status: fully booted
                [9] => 
                [10] => response: success
                [11] => message: command output follows
                [12] => output: local/218@from-queue-0000d6c2;1!from-queue!900!1!up!appqueue!(outgoing line)!2740629035!!!3!195!a4d133ed-66fb-48ea-8d92-44612bc5eff5!1603987701.156435
                [13] => output: local/218@from-queue-0000d6c2;2!macro-dial-one!s!55!up!dial!sip/218,,hhtrm(auto-blkvm)ib(func-apply-sipheaders^s^1)!27998229572!!!3!195!b4332d9f-d67a-46e3-9873-f1b950a8304e!1603987701.156436
                [14] => output: sip/2730919494-0000b598!ext-queues!900!39!up!playback!custom/seja_bem_vindo_nwt&custom/corona, !27988565364!!!3!1!!1603987894.156446
                [15] => output: sip/2730919494-0000b58c!ext-queues!900!51!up!queue!900,t,,,300,,,,,!27998229572!!!3!235!a4d133ed-66fb-48ea-8d92-44612bc5eff5!1603987661.156432
                [16] => output: sip/218-0000b58f!macro-dial-one!s!1!up!appdial!(outgoing line)!218!!!3!195!b4332d9f-d67a-46e3-9873-f1b950a8304e!1603987701.156437
                [17] => 
                [18] => response: goodbye
                [19] => message: thanks for all the fish.
                [20] => 
                */

                $KeyStart=array_search('message: command output follows', $wrets);
                $KeyEnd=array_search('response: goodbye', $wrets);
                $Buffer=[];
                for($i=$KeyStart+1;$i<$KeyEnd;$i++) {
                    if( $wrets[$i][1] ) {
                        $Key[]=explode(':',$wrets[$i])[1];
                    }
                }


                foreach( $Key as $value ) {
                    $KeyAux[] = explode('!', trim( $value ) );

                    for($x=0;$x<count($KeyAux);$x++) {
                        $Buffer[ @$KeyAux[$x][0] ] = @$KeyAux[ $x ];
                        //$Buffer[ @$KeyAux[$x][12] ] = @$KeyAux[ $x ];
                    }
                }
               
            }
            
            /* echo('<pre>');
            print_r($Buffer); */
            //print_r(count($Key));

            return json_encode( $Buffer, JSON_PRETTY_PRINT );
        }
    }
}

?>