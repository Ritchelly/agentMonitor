$('document').ready(function() {
      BuildTable();
      AtualizarAutomatico=setInterval(function(){BuildTable();},1000);
});


async function GetData(url) {
    var url=url;
    var rs;
    $.ajax({
        type:'post',
        url:url,
        cache:false,
        dataType:"json",
        async:false,
        error:function() {
            //     alert('Erro ao carregar informações da ligação');
        },
        success: function(result) {
            rs=result;
        }
    });
    return rs;
}

function secondsToTime(secs)
{
    secs = Math.round(secs);
    var hours = Math.floor(secs / (60 * 60));

    var divisor_for_minutes = secs % (60 * 60);
    var minutes = Math.floor(divisor_for_minutes / 60);

    var divisor_for_seconds = divisor_for_minutes % 60;
    var seconds = Math.ceil(divisor_for_seconds);

    if(seconds<10) {
        seconds='0'+seconds;
    }

    if(minutes<10) {
        minutes='0'+minutes;
    }
    return minutes+':'+seconds;
}

async function Get_Channels_Consise() {
    const AllChannels     = await GetData('../amiclass/c2c.php?ChannelsConcise');
    const QtdKeyChannels  = Object.keys(AllChannels).length;
    let ChannelsDialing = [];

        console.log(AllChannels);

    for( i=0; i<QtdKeyChannels; i++ ) {
        let Channel = AllChannels[ Object.keys(AllChannels)[i] ];

        /* if( Channel[5] == 'dial' ) {
            ChannelsDialing.push(Channel);
        } */

        switch(Channel[5]) {
            case 'qppqueue': //Quem está efetuando
            case 'queue': //Quem está efetuando
            //case 'dial': //Quem está efetuando
            //case 'appdial': //Quem está recebendo
                ChannelsDialing.push(Channel);
            break;
        }

    }

    return ChannelsDialing;
}

async function BuildTable() {
    const ChannelsDialing = await Get_Channels_Consise();
    let agents_online = 0;
    let agents_idle   = 0;
    let agents_oncall = 0;
    let agents_onring = 0;

    $("#tbody-agent-on-call").children().remove();
    
    /*0: "sip/1102-00000204"
    1: "interno"
    2: "33988151213"
    3: "4"
    4: "ring"
    5: "dial"
    6: "sip/telenova/033988151213,,tt"
    7: "1102"
    8: ""
    9: ""
    10: "3"
    11: "8"
    12: ""
    13: "1603983904.785" */

    ChannelsDialing.forEach( channel => {
            console.log(channel);
          

            switch(channel[4]) {
                case 'ring'://Ligação Chamando;
                    /* agents_onring++;
                    AgentStatus   = 'Chamando...'
                    CollorLine    = '#ff9100';
                    AgentStatusID = 0;
                    Time          = parseInt(channel[11]);
                    break; */
                case 'ringing'://Ligação Recebendo;
                    agents_onring++;
                    AgentStatus   = 'Chamando...'
                    CollorLine    = '#0000FF';
                    AgentStatusID = 0;
                    Time          = parseInt(channel[11]);
                    break;
                case 'up'://Agente em Ligação;
                    agents_oncall++;
                    AgentStatus   = 'Em Ligação...'
                    CollorLine    = '#008000';
                    AgentStatusID = 0;
                    Time          = parseInt(channel[11]);
                    break;
            }


            Time = secondsToTime(Time);
            let ChannelTrunk = channel[6].split(',');

       // $('#tbody-agent-on-call').append('<tr  style="color:'+CollorLine+';font-weight: bold"><td>'+channel[7]+'</td><td style="text-align: center">'+channel[2]+'</td><td style="text-align: center">'+Time+'</td><td>'+AgentStatus+'</td><td style="text-align: center">'+ChannelTrunk[0]+'</td></tr>');
        $('#tbody-agent-on-call').append('<tr  style="color:'+CollorLine+';font-weight: bold"><td>'+channel[2]+'</td><td style="text-align: center">'+channel[7]+'</td><td style="text-align: center">'+Time+'</td><td>'+AgentStatus+'</td><td style="text-align: center">'+ChannelTrunk[0]+'</td></tr>');

    });

    $('#lbQtdAgentOnline').html('On-Line : '+agents_online);
    $('#lbQtdAgentOnIdle').html('Em Espera : '+agents_idle);
    $('#lbQtdAgentOnCall').html('Em Ligação : '+agents_oncall);
    $('#lbQtdAgentOnRing').html('Chamando : '+agents_onring);
}