# Oauth2 Autolink #

Este plugin realiza o insert dos dados referentes a usuários que são criados no ambiente moodle na tabela "mdl_auth_oauth2_linked_login".

Os gatilhos de eventos são obtidos através da função user_created.


## Instalando via arquivo ZIP carregado ##

1. Entre no seu site Moodle como administrador e acesse _Administração do site >
   Plugins > Instalar plugins_.
2. Carregue o arquivo ZIP com o código do plugin. Você só será solicitado a adicionar
   detalhes extras caso o seu tipo de plugin não seja detectado automaticamente.
3. Verifique o relatório de validação do plugin e conclua a instalação.

## Instalando manualmente ##

O plugin também pode ser instalado colocando o conteúdo deste diretório em:

    {seu/moodle/raiz}/local/oauth2_autolink

Em seguida, faça login no seu site Moodle como administrador e acesse _Administração do site >
Notificações_ para concluir a instalação.

Como alternativa, você pode executar:

    $ php admin/cli/upgrade.php

para concluir a instalação a partir da linha de comando.

OBS: Após identificação do primeiro gatilho (user_created), teremos uma nova pasta na raiz do plugin chamado logs. Ele é essencial para controle de auditoria e visualização dos registros de funcionamento do plugin em Administração do site -> Relatórios -> Oauth2 Autolink.

## License ##

2025 Alan Araújo <Alan.araujo2625@gmail.com>

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <https://www.gnu.org/licenses/>.
