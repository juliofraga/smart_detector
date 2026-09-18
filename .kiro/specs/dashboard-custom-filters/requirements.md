# Documento de Requisitos: Dashboard Custom Filters

## Introdução

Esta funcionalidade permite que administradores marquem atributos de eventos customizados como "Filtro de Dashboard". Atributos assim marcados passam a aparecer como campos de busca na tela de Dashboards do SmartDetector, e os valores preenchidos pelo usuário são aplicados como filtros `LIKE` em todos os agregados exibidos (total de eventos, intrusões, normais, por dia, por classificação e por tipo).

---

## Glossário

- **EventAttributeController**: Controller PHP responsável pelo CRUD de atributos de eventos customizados e pelo endpoint de filtros de dashboard.
- **EventController**: Controller PHP responsável pelo ingest de eventos e pelo endpoint de dados do dashboard (`getDashboards`).
- **event_attribute**: Registro na tabela `event_attributes` que define um campo customizado associado à tabela `events`.
- **dashboard_filter**: Coluna `tinyint` (0 ou 1) na tabela `event_attributes` que indica se o atributo é exposto como campo de filtro na tela de Dashboards.
- **enabled**: Coluna `tinyint` na tabela `event_attributes` que indica se o atributo aceita dados no ingest de eventos.
- **customFilters**: Conjunto de pares `{field_name: value}` extraídos da query string da requisição ao dashboard, excluindo os parâmetros reservados `from`, `to` e `ids`.
- **Dashboard**: Tela do SmartDetector que exibe agregados de eventos (totais, por dia, por classificação, por tipo).
- **Dashboards.vue**: Componente Vue.js que renderiza a tela de Dashboards, incluindo campos de filtro customizados.
- **EventAttributes.vue**: Componente Vue.js para gerenciamento de atributos de eventos customizados.
- **Sistema**: O SmartDetector como um todo, incluindo backend Laravel e frontend Vue.js.
- **API**: Camada REST do SmartDetector acessível via `/api/v1/`.
- **FieldNameValidator**: Trait PHP que valida nomes de campo com a regex `[a-zA-Z_][a-zA-Z0-9_]*`.

---

## Requisitos

### Requisito 1: Campo dashboard_filter no Modelo de Dados

**User Story:** Como administrador, quero que a tabela `event_attributes` suporte a marcação de filtros de dashboard, para que eu possa configurar quais atributos customizados aparecem na tela de Dashboards.

#### Critérios de Aceitação

1. THE Sistema SHALL armazenar o campo `dashboard_filter` como `tinyint` com valor padrão `0` na tabela `event_attributes`.
2. THE Sistema SHALL aceitar somente os valores `0` (desativado) e `1` (ativado) para o campo `dashboard_filter`.
3. THE Sistema SHALL incluir `dashboard_filter` na lista de campos preenchíveis (`fillable`) do model `event_attribute`.

---

### Requisito 2: Invariante enabled/dashboard_filter

**User Story:** Como administrador, quero que um atributo desabilitado não possa ser usado como filtro de dashboard, para que o sistema mantenha consistência entre habilitação e exposição de filtros.

#### Critérios de Aceitação

1. WHEN um administrador atualiza um `event_attribute` com `enabled = 0`, THE EventAttributeController SHALL definir `dashboard_filter = 0` no mesmo registro antes de persistir a alteração.
2. WHILE um `event_attribute` possui `enabled = 0`, THE Sistema SHALL manter `dashboard_filter = 0` nesse registro.
3. IF um `event_attribute` com `enabled = 0` receber uma requisição de atualização com `dashboard_filter = 1`, THEN THE EventAttributeController SHALL ignorar o valor enviado e persistir `dashboard_filter = 0`.

---

### Requisito 3: Endpoint de Filtros de Dashboard

**User Story:** Como usuário autenticado, quero consultar quais atributos de eventos estão configurados como filtros de dashboard, para que o frontend possa renderizar os campos corretos na tela de Dashboards.

#### Critérios de Aceitação

1. WHEN uma requisição autenticada é feita para `GET /api/v1/event-attribute/dashboard-filters`, THE EventAttributeController SHALL retornar somente os registros com `dashboard_filter = 1` AND `enabled = 1`.
2. WHEN o endpoint `dashboard-filters` é chamado, THE EventAttributeController SHALL retornar para cada registro os campos `id`, `field_name` e `display_value`.
3. WHEN o endpoint `dashboard-filters` é chamado, THE EventAttributeController SHALL retornar os registros ordenados por `display_value` em ordem ascendente.
4. IF nenhum atributo satisfizer as condições `dashboard_filter = 1 AND enabled = 1`, THEN THE EventAttributeController SHALL retornar um array vazio sem erro.
5. THE API SHALL exigir autenticação JWT para acessar `GET /api/v1/event-attribute/dashboard-filters`.
6. WHERE o perfil do usuário autenticado seja `Usuário` ou `Administrador`, THE API SHALL permitir o acesso a `GET /api/v1/event-attribute/dashboard-filters`.

---

### Requisito 4: Coleta e Propagação de Filtros Customizados no Backend

**User Story:** Como usuário, quero que os valores dos filtros customizados preenchidos no dashboard sejam aplicados a todos os agregados exibidos, para que os totais e gráficos reflitam exatamente o subconjunto de eventos que me interessa.

#### Critérios de Aceitação

1. WHEN uma requisição é feita para `GET /api/v1/event/get/dashboards` com parâmetros de query além de `from`, `to` e `ids`, THE EventController SHALL extrair esses parâmetros adicionais como `customFilters`.
2. WHEN `customFilters` contém ao menos um par `{field: value}` com valor não-vazio, THE EventController SHALL aplicar uma cláusula `WHERE field LIKE '%value%'` nos agregados `totalEvents`, `totalIntrusions`, `totalNormal`, `totalsByDay`, `classifications` e `types`.
3. THE EventController SHALL aplicar o mesmo conjunto de `customFilters` a todos os 6 agregados retornados por `getDashboards`.
4. IF o valor de um filtro customizado for `null` ou string vazia, THEN THE EventController SHALL omitir a cláusula `WHERE` correspondente para esse filtro.
5. WHEN `customFilters` é aplicado a agregados que utilizam JOIN com outras tabelas, THE EventController SHALL prefixar o nome da coluna com `events.` para evitar ambiguidade.

---

### Requisito 5: Configuração do Filtro no Cadastro de Atributos (Frontend)

**User Story:** Como administrador, quero configurar se um atributo de evento é um filtro de dashboard diretamente nos formulários de criação e edição de atributos, para que eu possa gerenciar essa configuração sem sair da tela de atributos.

#### Critérios de Aceitação

1. WHEN o modal de adição de atributo está aberto, THE EventAttributes.vue SHALL exibir um campo select com as opções "Sim" e "Não" para `dashboard_filter`.
2. WHEN o modal de edição de atributo está aberto, THE EventAttributes.vue SHALL exibir um campo select com as opções "Sim" e "Não" para `dashboard_filter`, pré-selecionado com o valor atual do registro.
3. WHEN o formulário de adição é enviado, THE EventAttributes.vue SHALL incluir o valor de `dashboard_filter` no payload enviado ao backend.
4. WHEN o formulário de edição é enviado, THE EventAttributes.vue SHALL incluir o valor de `dashboard_filter` no payload enviado ao backend.
5. WHEN o formulário de adição é limpo após um cadastro bem-sucedido, THE EventAttributes.vue SHALL redefinir o campo `dashboard_filter` para o valor padrão `0`.
6. WHEN a listagem de atributos é exibida, THE EventAttributes.vue SHALL exibir o valor de `dashboard_filter` de cada registro na coluna correspondente da tabela.

---

### Requisito 6: Renderização Dinâmica dos Filtros no Dashboard (Frontend)

**User Story:** Como usuário, quero ver os campos de filtro customizados na tela de Dashboards automaticamente, conforme os atributos configurados pelo administrador, para que eu possa filtrar os dados sem precisar conhecer a estrutura interna do sistema.

#### Critérios de Aceitação

1. WHEN o componente `Dashboards.vue` é montado, THE Dashboards.vue SHALL chamar `GET /api/v1/event-attribute/dashboard-filters` para carregar os filtros disponíveis.
2. WHEN a resposta de `dashboard-filters` é recebida com ao menos um atributo, THE Dashboards.vue SHALL renderizar um campo de texto individual para cada atributo retornado, usando `display_value` como rótulo visível.
3. WHEN os campos de filtro customizados são renderizados, THE Dashboards.vue SHALL inicializar o valor reativo de cada campo com string vazia.
4. IF a chamada a `dashboard-filters` falhar, THEN THE Dashboards.vue SHALL continuar operando sem campos de filtro customizados, sem exibir erro bloqueante ao usuário.
5. WHEN o usuário preenche ao menos um campo de filtro customizado e aciona a busca, THE Dashboards.vue SHALL appendar cada par `field_name=value` não-vazio à URL da requisição ao dashboard, com ambos os componentes codificados via `encodeURIComponent`.
6. IF o valor de um campo de filtro customizado estiver vazio, THEN THE Dashboards.vue SHALL omitir esse parâmetro da URL da requisição ao dashboard.

---

### Requisito 7: Reset dos Filtros no Dashboard (Frontend)

**User Story:** Como usuário, quero poder limpar todos os filtros ativos no dashboard com um único clique, para que eu possa retornar à visualização padrão de forma rápida.

#### Critérios de Aceitação

1. WHEN o usuário clica no botão de limpar filtros, THE Dashboards.vue SHALL redefinir todos os valores de filtros customizados para string vazia.
2. WHEN o usuário clica no botão de limpar filtros, THE Dashboards.vue SHALL recarregar os dados do dashboard sem os filtros customizados após o reset.

---

### Requisito 8: Traduções da Interface

**User Story:** Como usuário de qualquer idioma suportado, quero ver os rótulos da funcionalidade de filtro de dashboard no meu idioma, para que a interface seja consistente com o restante do sistema.

#### Critérios de Aceitação

1. THE Sistema SHALL fornecer a chave de tradução `dashboard_filter` nos arquivos de idioma `pt_BR`, `en`, `es` e `fr`.
2. WHEN o rótulo `dashboard_filter` é exibido na interface, THE Sistema SHALL utilizar a tradução correspondente ao locale ativo do usuário.
