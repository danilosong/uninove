
<div id="messenger-box">

    <div class="chat-window">
        <div class="col-md-12">
            <div class="row">
                <div class="panel panel-usr">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-user"></i>&nbsp;&nbsp;&nbsp; <em id="nameusr">Danilo Dorotheu</em>
                            <i id="chat-back" class="fa fa-arrow-left pull-right"></i></h3>
                    </div>
                    <div class="panel-body">
                        <div id="chat-group-users" class="row">
                        </div>
                        <div class="row">
                            <div class="chat-view"></div>
                        </div>
                        <!--
                        <div class="checkbox">
                            <label><input id="send-enter" type="checkbox" checked="true" value="">Enviar mensagem com enter</label>
                        </div>
                        -->
                        <div class="row">
                            <textarea  id="msg-chat" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="row">
                            <button id="chat-send" type="button" class="btn btn-sm btn-block btn-usr">Enviar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="chat-list">
        <div class="col-md-12">
            <div class="chat-header-list">
                <div class="col-md-2">
                    <br>
                    <div class="row">
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
                                <i class="fa fa-bars"></i> 
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                                <li role="presentation" class="dropdown-header">Funcoes</li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="#"><i class="fa fa-user-plus"></i> Adicionar</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="#"><i class="fa fa-cogs"></i></i> Perfil</a></li>
                                <li role="presentation" class="divider"></li>
                                <li role="presentation" class="dropdown-header">Status</li>
                                <li role="presentation"><a class="setOnline"><i class="fa fa-check-circle-o"></i> Online</a></li>
                                <li role="presentation"><a class="setBusy"><i class="fa fa-ban"></i> Ocupado</a></li>
                                <li role="presentation"><a class="setOffline"><i class="fa fa-circle-o"></i> Ausente</a></li>
                            </ul>
                        </div>
                    </div>
                </div> 
                <div class="col-md-8">
                    <div class="row text-center">
                        <br>
                        <img class="img-circle" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Generic placeholder image" height="110" width="110">
                    </div>
                </div>
                <div class="row">&nbsp;</div>
                <div class="row">
                    <h3 id="username" class="text-center"></h3>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <input type="text" id="text-search" class="form-control" placeholder="Procurar...">
                            <span class = "input-group-btn">
                                <button id="btn-search" class="btn btn-primary" type="button"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="btn-group show-users">
                            <button type="button" class="btn btn-default active view-all">Todos</button>
                            <button type="button" class="btn btn-default view-online">Online</button>
                            <button type="button" class="btn btn-default view-groups">Grupos</button>
                        </div>
                    </div>
                </div>
                <br>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div id="chat-contacts"></div>
                </div>
            </div>
        </div>
    </div>
</div>

