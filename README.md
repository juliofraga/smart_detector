Projeto: Detecção Inteligente de Ameaças em Aplicações Web<br>
Documento: Descrição dos Requisitos Funcionais e Não Funcionais

ID: RF001<br>
TIPO: Funcional<br>
PRIORIDADE: Alta<br>
TÍTULO: Cadastro de Usuário<br>
DESCRIÇÃO: O sistema deve permitir que um novo usuário seja cadastrado, fornecendo nome, e-mail, senha e perfil (usuário ou administrador). O e-mail deve ser único, portanto, não podem haver e-mails duplicados no sistema.<br>
CRITÉRIOS DE ACEITAÇÃO<br>
    • Usuário preenche todos os campos e após submetê-los, o usuário é cadastrado com sucesso.<br>
    • Usuário preenche todos os campos e após submetê-los, o sistema exibe um alerta, caso o e-mail já exista, não permitindo que o usuário seja cadastrado.<br>


ID: RF002<br>
TIPO: Funcional<br>
PRIORIDADE: Alta<br>
TÍTULO: Edição de Usuário<br>
DESCRIÇÃO: O sistema deve permitir que um usuário existente tenha seus dados editados, exceto o e-mail, que não poderá ser alterado.<br>
CRITÉRIOS DE ACEITAÇÃO<br>
    • Usuário existente é selecionado para edição e tem seus dados alterados com sucesso.<br>
    • O campo de e-mail deve estar desabilitado para edição na tela de usuário.<br>


ID: RF003<br>
TIPO: Funcional<br>
PRIORIDADE: Alta<br>
TÍTULO: Exclusão de Usuário<br>
DESCRIÇÃO: O sistema deve permitir que um usuário existente seja excluído, removendo-o definitivamente do cadastro.<br>
CRITÉRIOS DE ACEITAÇÃO<br>
    • Usuário existente é selecionado para exclusão e, após confirmação, não deve mais constar no sistema.<br>


ID: RF004<br>
TIPO: Funcional<br>
PRIORIDADE: Média<br>
TÍTULO: Tela de Gestão de Usuários<br>
DESCRIÇÃO: O sistema deve disponibilizar uma tela de gestão de usuários, permitindo operações de inclusão, edição e exclusão.<br>
CRITÉRIOS DE ACEITAÇÃO<br>
    • Usuário acessa a tela de gestão de usuários.<br>
    • Usuário pode adicionar um novo usuário.<br>
    • Usuário pode editar um usuário existente.<br>
    • Usuário pode excluir um usuário existente.<br>


ID: RF005<br>
TIPO: Funcional<br>
PRIORIDADE: Média<br>
TÍTULO: Perfis de Usuários<br>
DESCRIÇÃO: O sistema deve suportar dois perfis de usuário: administrador e usuário.<br>
Usuário administrador pode manipular (inserir, editar e excluir) qualquer usuário, além de acessar todas as funcionalidades.<br>
Usuário comum pode apenas editar o próprio cadastro, além de visualizar o painel e consultar eventos, sem manipular outros usuários.<br>
CRITÉRIOS DE ACEITAÇÃO<br>
    • Usuário administrador consegue manipular (inserir, editar e excluir) usuários no sistema.<br>
    • Usuário comum não consegue manipular outros usuários.<br>
    • Usuário comum consegue editar apenas o próprio cadastro.<br>


ID: RF006<br>
TIPO: Funcional<br>
PRIORIDADE: Alta<br>
TÍTULO: Login de Usuário<br>
DESCRIÇÃO: O sistema deve permitir que um usuário cadastrado acesse utilizando e-mail e senha válidos.<br>
CRITÉRIOS DE ACEITAÇÃO<br>
    • Usuário insere e-mail e senha corretos e acessa o sistema.<br>
    • Tentativas com e-mail inexistente e/ou senha incorreta não permitem acesso.<br>


ID: RF007<br>
TIPO: Funcional<br>
PRIORIDADE: Alta<br>
TÍTULO: Logout<br>
DESCRIÇÃO: O sistema deve permitir que o usuário realize logout, encerrando sua sessão atual.<br>
CRITÉRIOS DE ACEITAÇÃO<br>
    • Usuário realiza logout e é redirecionado para a tela de login.<br>
    • Após logout, o usuário não pode acessar o sistema até efetuar novo login.<br>


ID: RF008<br>
TIPO: Funcional<br>
PRIORIDADE: Alta<br>
TÍTULO: Armazenamento de Evento<br>
DESCRIÇÃO: O sistema deve registrar no banco de dados todos os eventos identificados como possíveis anomalias ou ameaças, armazenando: Descrição, IP, Tipo de Ameaça, Classificação da Ameaça, Análise da IA, Origem Geográfica, Request, Data e Hora.<br>
CRITÉRIOS DE ACEITAÇÃO<br>
    • Após detecção, os dados do evento são registrados corretamente no banco de dados.<br>


ID: RF009<br>
TIPO: Funcional<br>
PRIORIDADE: Alta<br>
TÍTULO: Painel de Exibição de Eventos<br>
DESCRIÇÃO: O sistema deve exibir em tempo real os últimos 50 eventos detectados como possíveis anomalias ou ataques, incluindo: Descrição, IP, Tipo de Ameaça, Classificação de Risco (Baixo, Médio, Alto), Data e Hora.<br>
CRITÉRIOS DE ACEITAÇÃO<br>
    • Evento detectado deve ser exibido imediatamente no painel.<br>
    • Informações exibidas de forma clara (Descrição, IP, Tipo de Ameaça, Classificação de Risco).<br>
    • Listar os últimos 50 eventos, do mais recente para o mais antigo.<br>


ID: RF010<br>
TIPO: Funcional<br>
PRIORIDADE: Média<br>
TÍTULO: Consulta de Eventos<br>
DESCRIÇÃO: O sistema deve permitir ao usuário consultar eventos, aplicando filtros por Data, Hora, Tipo de Ameaça e Risco.<br>
CRITÉRIOS DE ACEITAÇÃO<br>
    • Por padrão, são exibidos os últimos 20 eventos, do mais recente para o mais antigo.<br>
    • Ao aplicar filtros, o sistema exibe os resultados correspondentes.<br>


ID: RF011<br>
TIPO: Funcional<br>
PRIORIDADE: Alta<br>
TÍTULO: Consulta de Eventos - Paginação<br>
DESCRIÇÃO: A tela de consulta de eventos deve possuir paginação devido ao grande volume de registros.<br>
CRITÉRIOS DE ACEITAÇÃO<br>
    • Usuário navega entre páginas utilizando os controles de paginação.<br>
    • Cada página exibe 20 eventos.<br>
    • Devem estar disponíveis os controles: página atual, anterior, próxima, primeira e última.<br>


ID: RF012<br>
TIPO: Funcional<br>
PRIORIDADE: Média<br>
TÍTULO: Consulta Detalhada de Evento<br>
DESCRIÇÃO: O sistema deve permitir a visualização detalhada de um evento, acessível a partir do painel principal ou da tela de consulta.<br>
CRITÉRIOS DE ACEITAÇÃO<br>
    • No painel de eventos, o usuário clica em um evento e abre uma nova aba com detalhes completos.<br>
    • Na tela de consulta de eventos, o usuário seleciona um evento e abre uma nova aba com detalhes completos.<br>


ID: RNF001<br>
TIPO: Não Funcional<br>
PRIORIDADE: Alta<br>
TÍTULO: Armazenamento de Senhas Criptografadas<br>
DESCRIÇÃO: O sistema deve armazenar senhas utilizando criptografia com bcrypt.<br>
CRITÉRIOS DE ACEITAÇÃO<br>
    • Senha não pode ser salva em texto plano no banco.<br>
    • Apenas o hash deve ser armazenado.<br>
    • Consulta direta ao banco não deve permitir leitura da senha.<br>


ID: RNF002<br>
TIPO: Não Funcional<br>
PRIORIDADE: Alta<br>
TÍTULO: Autenticação JWT<br>
DESCRIÇÃO: A API deve utilizar autenticação via JWT com tokens temporários.<br>
CRITÉRIOS DE ACEITAÇÃO<br>
    • Rotas protegidas devem rejeitar requisições sem token válido.<br>
    • Token deve expirar em 2 horas.<br>
    • Token expirado deve ser considerado inválido, bloqueando acesso.<br>


ID: RNF003<br>
TIPO: Não Funcional<br>
PRIORIDADE: Média<br>
TÍTULO: Rate Limiting no Login<br>
DESCRIÇÃO: O sistema deve implementar rate limiting para prevenir ataques de força bruta no login.<br>
CRITÉRIOS DE ACEITAÇÃO<br>
    • Após 5 tentativas falhas consecutivas, bloquear novas tentativas do mesmo IP/usuário por 10 minutos.<br>
    • O usuário deve ser informado claramente sobre o bloqueio temporário.<br>


ID: RNF004<br>
TIPO: Não Funcional<br>
PRIORIDADE: Alta<br>
TÍTULO: Latência de Exibição de Eventos<br>
DESCRIÇÃO: O sistema deve exibir os eventos em tempo real com latência máxima de 5 segundos entre detecção e exibição.<br>
CRITÉRIOS DE ACEITAÇÃO<br>
    • Eventos devem aparecer no painel em até 5 segundos após a detecção.<br>
    • O painel deve atualizar automaticamente, sem necessidade de refresh manual.<br>


ID: RNF005<br>
TIPO: Não Funcional<br>
PRIORIDADE: Média<br>
TÍTULO: Interface Responsiva<br>
DESCRIÇÃO: O sistema deve ter interface responsiva e acessível em desktop, tablet e dispositivos móveis.<br>
CRITÉRIOS DE ACEITAÇÃO<br>
    • O layout se ajusta automaticamente conforme a largura da tela.<br>
    • Em telas pequenas, o menu deve ser exibido no formato hambúrguer.<br>
    • Painel de Eventos, Consulta de Eventos e Gestão de Usuários devem estar acessíveis e funcionais em qualquer dispositivo.<br>