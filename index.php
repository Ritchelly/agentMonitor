<!DOCTYPE html>
<html>
<head>
    <title>Indicadores</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

       <link rel="stylesheet" href="plugins/mobile/jquery.mobile-1.4.5.min.css">

        <script src="plugins/mobile/jquery.min.js"></script>
        <script src="plugins/mobile/jquery.mobile-1.4.5.min.js"></script>

        <script src="plugins/jquery.ui/development-bundle/ui/jquery.ui.datepicker.js"></script>
        <link rel="stylesheet" href="plugins/jquery.ui/development-bundle/themes/base/jquery.ui.datepicker.css">
        <link rel="stylesheet" href="plugins/jquery.ui/development-bundle/themes/base/jquery.ui.all.css">
        <link rel="stylesheet" href="custom.css">
        <script src="node_modules/socket.io-client/dist/socket.io.js"></script>
        <script src="js/agentmonitor2.js"></script>
        <script src="js/sortable.js"></script>
</head>

<body>

<div data-role="page" id="page">
    <div data-role="footer" class="nav-glyphish-example" data-theme="b">
        <div data-role="navbar" class="nav-glyphish-example" data-grid="d">
            <ul>
                <li><a href="#panel" id="Opcoes" data-icon="custom">Opções</a></li>

            </ul>
        </div>
    </div>

    <div data-role="panel" id="panel" data-display="push" data-position="left" >

        <ul data-role="listview">
            <li>
                <a href="#" data-transition="flip" id="a-temporeal">Tempo Real</a>
            </li>
        </ul>
    </div>

    <div data-role="main" class="ui-content" >

        <div id="dv-producao-sala-charts" style="display: none">
             <div id="dvInfo"  ></div>
             <div id="dvQtdDoacoes" style="width: 100%;"  ></div>
             <div id="dvProducaoCategoria" class="charts" ></div>
             <div id="dvProducaoAgrupada"  class="charts"   ></div>
             <div id="dvTxOcupacao" class="charts"  style="width: 100%;display: none" ></div>
        </div>

        <div id="dv-agent-monitor">
            <div id="dv-agent-monitor-icons" style="width: 100%; text-align: center">
                <div id="dv-agent-info-online" class="dv-agent-info">
                    <img class="img-agentstatus" src="imagens/operator3.png" >
                    <h4 id="lbQtdAgentOnline">On-line </h4>
                </div>
                <div id="dv-agent-info-oncall" class="dv-agent-info">
                    <img class="img-agentstatus" src="imagens/call37.png" >
                    <h4 id="lbQtdAgentOnCall">Em Ligação </h4>
                </div>
                <div id="dv-agent-info-onring" class="dv-agent-info">
                    <img class="img-agentstatus" src="imagens/telephoneRing.png" >
                    <h4 id="lbQtdAgentOnRing">Chamando  </h4>
                </div>
                <!-- <div id="dv-agent-info-onhold" class="dv-agent-info" >
                    <img class="img-agentstatus" src="imagens/businessman32.png"  >
                    <h4 id="lbQtdAgentOnIdle">Em Espera </h4>
                </div> -->
            </div>

            <div class="ui-corner-all custom-corners" style="width: 100% ;position: relative; float: left">
                <div class="ui-bar ui-bar-a">
                    <h3>Agentes On-Line </h3>
                </div>
                <div class="ui-body ui-body-a">
                    <table data-role="table" id="table-agent-on-call"  class="ui-body-d ui-shadow table-stripe ui-responsive " data-column-btn-theme="b" data-column-popup-theme="a">
                            <thead>
                                <th>Ramal</th>
                                <th style="text-align: center">Número</th>
                                <th id="table-agent-oncall-th-tempo" style="text-align: center">Tempo</th>
                                <th>Status</th>
                                <th style="text-align: center">Tronco</th>
                            </thead>
                        <tbody id="tbody-agent-on-call">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
