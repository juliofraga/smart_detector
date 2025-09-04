Projeto: Detecção Inteligente de Ameaças em Aplicações Web
Documento: Descrição dos Requisitos Funcionais e Não Funcionais

ID: RF001
TIPO: Funcional
PRIORIDADE: Alta
TÍTULO: Cadastro de Usuário
DESCRIÇÃO: O sistema deve permitir que um novo usuário seja cadastrado, fornecendo nome, e-mail, senha e perfil (usuário ou administrador). O e-mail deve ser único, portanto, não podem haver e-mails duplicados no sistema.
CRITÉRIOS DE ACEITAÇÃO
    • Usuário preenche todos os campos e após submetê-los, o usuário é cadastrado com sucesso.
    • Usuário preenche todos os campos e após submetê-los, o sistema exibe um alerta, caso o e-mail já exista, não permitindo que o usuário seja cadastrado.


ID: RF002
TIPO: Funcional
PRIORIDADE: Alta
TÍTULO: Edição de Usuário
DESCRIÇÃO: O sistema deve permitir que um usuário existente tenha seus dados editados, exceto o e-mail, que não poderá ser alterado.
CRITÉRIOS DE ACEITAÇÃO
    • Usuário existente é selecionado para edição e tem seus dados alterados com sucesso.
    • O campo de e-mail deve estar desabilitado para edição na tela de usuário.


ID: RF003
TIPO: Funcional
PRIORIDADE: Alta
TÍTULO: Exclusão de Usuário
DESCRIÇÃO: O sistema deve permitir que um usuário existente seja excluído, removendo-o definitivamente do cadastro.
CRITÉRIOS DE ACEITAÇÃO
    • Usuário existente é selecionado para exclusão e, após confirmação, não deve mais constar no sistema.


ID: RF004
TIPO: Funcional
PRIORIDADE: Média
TÍTULO: Tela de Gestão de Usuários
DESCRIÇÃO: O sistema deve disponibilizar uma tela de gestão de usuários, permitindo operações de inclusão, edição e exclusão.
CRITÉRIOS DE ACEITAÇÃO
    • Usuário acessa a tela de gestão de usuários.
    • Usuário pode adicionar um novo usuário.
    • Usuário pode editar um usuário existente.
    • Usuário pode excluir um usuário existente.


ID: RF005
TIPO: Funcional
PRIORIDADE: Média
TÍTULO: Perfis de Usuários
DESCRIÇÃO: O sistema deve suportar dois perfis de usuário: administrador e usuário.
Usuário administrador pode manipular (inserir, editar e excluir) qualquer usuário, além de acessar todas as funcionalidades.
Usuário comum pode apenas editar o próprio cadastro, além de visualizar o painel e consultar eventos, sem manipular outros usuários.
CRITÉRIOS DE ACEITAÇÃO
    • Usuário administrador consegue manipular (inserir, editar e excluir) usuários no sistema.
    • Usuário comum não consegue manipular outros usuários.
    • Usuário comum consegue editar apenas o próprio cadastro.


ID: RF006
TIPO: Funcional
PRIORIDADE: Alta
TÍTULO: Login de Usuário
DESCRIÇÃO: O sistema deve permitir que um usuário cadastrado acesse utilizando e-mail e senha válidos.
CRITÉRIOS DE ACEITAÇÃO
    • Usuário insere e-mail e senha corretos e acessa o sistema.
    • Tentativas com e-mail inexistente e/ou senha incorreta não permitem acesso.


ID: RF007
TIPO: Funcional
PRIORIDADE: Alta
TÍTULO: Logout
DESCRIÇÃO: O sistema deve permitir que o usuário realize logout, encerrando sua sessão atual.
CRITÉRIOS DE ACEITAÇÃO
    • Usuário realiza logout e é redirecionado para a tela de login.
    • Após logout, o usuário não pode acessar o sistema até efetuar novo login.


ID: RF008
TIPO: Funcional
PRIORIDADE: Alta
TÍTULO: Armazenamento de Evento
DESCRIÇÃO: O sistema deve registrar no banco de dados todos os eventos identificados como possíveis anomalias ou ameaças, armazenando: Descrição, IP, Tipo de Ameaça, Classificação da Ameaça, Análise da IA, Origem Geográfica, Request, Data e Hora.
CRITÉRIOS DE ACEITAÇÃO
    • Após detecção, os dados do evento são registrados corretamente no banco de dados.


ID: RF009
TIPO: Funcional
PRIORIDADE: Alta
TÍTULO: Painel de Exibição de Eventos
DESCRIÇÃO: O sistema deve exibir em tempo real os últimos 50 eventos detectados como possíveis anomalias ou ataques, incluindo: Descrição, IP, Tipo de Ameaça, Classificação de Risco (Baixo, Médio, Alto), Data e Hora.
CRITÉRIOS DE ACEITAÇÃO
    • Evento detectado deve ser exibido imediatamente no painel.
    • Informações exibidas de forma clara (Descrição, IP, Tipo de Ameaça, Classificação de Risco).
    • Listar os últimos 50 eventos, do mais recente para o mais antigo.


ID: RF010
TIPO: Funcional
PRIORIDADE: Média
TÍTULO: Consulta de Eventos
DESCRIÇÃO: O sistema deve permitir ao usuário consultar eventos, aplicando filtros por Data, Hora, Tipo de Ameaça e Risco.
CRITÉRIOS DE ACEITAÇÃO
    • Por padrão, são exibidos os últimos 20 eventos, do mais recente para o mais antigo.
    • Ao aplicar filtros, o sistema exibe os resultados correspondentes.


ID: RF011
TIPO: Funcional
PRIORIDADE: Alta
TÍTULO: Consulta de Eventos - Paginação
DESCRIÇÃO: A tela de consulta de eventos deve possuir paginação devido ao grande volume de registros.
CRITÉRIOS DE ACEITAÇÃO
    • Usuário navega entre páginas utilizando os controles de paginação.
    • Cada página exibe 20 eventos.
    • Devem estar disponíveis os controles: página atual, anterior, próxima, primeira e última.


ID: RF012
TIPO: Funcional
PRIORIDADE: Média
TÍTULO: Consulta Detalhada de Evento
DESCRIÇÃO: O sistema deve permitir a visualização detalhada de um evento, acessível a partir do painel principal ou da tela de consulta.
CRITÉRIOS DE ACEITAÇÃO
    • No painel de eventos, o usuário clica em um evento e abre uma nova aba com detalhes completos.
    • Na tela de consulta de eventos, o usuário seleciona um evento e abre uma nova aba com detalhes completos.


ID: RNF001
TIPO: Não Funcional
PRIORIDADE: Alta
TÍTULO: Armazenamento de Senhas Criptografadas
DESCRIÇÃO: O sistema deve armazenar senhas utilizando criptografia com bcrypt.
CRITÉRIOS DE ACEITAÇÃO
    • Senha não pode ser salva em texto plano no banco.
    • Apenas o hash deve ser armazenado.
    • Consulta direta ao banco não deve permitir leitura da senha.


ID: RNF002
TIPO: Não Funcional
PRIORIDADE: Alta
TÍTULO: Autenticação JWT
DESCRIÇÃO: A API deve utilizar autenticação via JWT com tokens temporários.
CRITÉRIOS DE ACEITAÇÃO
    • Rotas protegidas devem rejeitar requisições sem token válido.
    • Token deve expirar em 2 horas.
    • Token expirado deve ser considerado inválido, bloqueando acesso.


ID: RNF003
TIPO: Não Funcional
PRIORIDADE: Média
TÍTULO: Rate Limiting no Login
DESCRIÇÃO: O sistema deve implementar rate limiting para prevenir ataques de força bruta no login.
CRITÉRIOS DE ACEITAÇÃO
    • Após 5 tentativas falhas consecutivas, bloquear novas tentativas do mesmo IP/usuário por 10 minutos.
    • O usuário deve ser informado claramente sobre o bloqueio temporário.


ID: RNF004
TIPO: Não Funcional
PRIORIDADE: Alta
TÍTULO: Latência de Exibição de Eventos
DESCRIÇÃO: O sistema deve exibir os eventos em tempo real com latência máxima de 5 segundos entre detecção e exibição.
CRITÉRIOS DE ACEITAÇÃO
    • Eventos devem aparecer no painel em até 5 segundos após a detecção.
    • O painel deve atualizar automaticamente, sem necessidade de refresh manual.


ID: RNF005
TIPO: Não Funcional
PRIORIDADE: Média
TÍTULO: Interface Responsiva
DESCRIÇÃO: O sistema deve ter interface responsiva e acessível em desktop, tablet e dispositivos móveis.
CRITÉRIOS DE ACEITAÇÃO
    • O layout se ajusta automaticamente conforme a largura da tela.
    • Em telas pequenas, o menu deve ser exibido no formato hambúrguer.
    • Painel de Eventos, Consulta de Eventos e Gestão de Usuários devem estar acessíveis e funcionais em qualquer dispositivo.