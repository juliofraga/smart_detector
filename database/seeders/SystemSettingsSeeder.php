<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\system_setting;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        system_setting::create([
            'attribute' => 'request_per_minute',
            'title' => 'Número Máximo de Requisições Por Minuto Na Inserção de Eventos de Anomalias (máximo: 5000).',
            'description' => 'O valor definido nesta opção determina o némero máximo de requisições por minuto permitidas para a inserção de eventos de anomalias. Esse limite pode impactar o funcionamento da tela Home e o modo como os eventos são registrados no sistema, já que, ao atingir o número máximo, alguns eventos poderão ser descartados.',
            'type' => 'text',
            'value' => '1000',
            'orderby' => '6',
            'active' => 1
        ]);
        system_setting::create([
            'attribute' => 'block_user',
            'title' => 'Ativar Bloqueador de Usuário Após 5 Erros Consecutivos de Senha.',
            'description' => 'Quando esta opção estiver ativada, o usuário será bloqueado após errar a senha 5 vezes consecutivas. Caso esteja desativada, o usuário não será bloqueado, mesmo após 5 tentativas incorretas.',
            'type' => 'YesNo',
            'value' => 'Yes',
            'orderby' => '1',
            'active' => 1
        ]);
        system_setting::create([
            'attribute' => 'pass_complexity',
            'title' => 'Ativar Complexidade de Senha.',
            'description' => 'Com esta opção ativada, o usuário deverá definir uma senha que atenda aos requisitos de complexidade. Caso esteja desativada, o usuário poderá utilizar senhas sem qualquer critério de complexidade.',
            'type' => 'YesNo',
            'value' => 'Yes',
            'orderby' => '2',
            'active' => 1
        ]);
        system_setting::create([
            'attribute' => 'timezone_selected',
            'title' => 'Selecione o Time Zone.',
            'description' => 'Selecione o time zone que será considerado na utilização do sistema. Na dúvida de como configurar o time zone, consulte os valores disponíveis: https://www.php.net/manual/en/timezones.php',
            'type' => 'select',
            'value' => 'America/Sao_Paulo',
            'orderby' => '7',
            'active' => 1
        ]);
        system_setting::create([
            'attribute' => 'llm_standard',
            'title' => 'LLM Padrão',
            'description' => 'O modelo selecionado será o modelo utilizado para fazer as análises dos eventos de anomalia.',
            'type' => 'select',
            'value' => '0',
            'orderby' => '8',
            'active' => 1
        ]);
        system_setting::create([
            'attribute' => 'llm_prompt',
            'title' => 'LLM - Prompt',
            'description' => 'Escreva o prompt desejado para a IA processar. Insira uma instrução clara, informando o contexto e o resultado esperado.',
            'type' => 'textarea',
            'value' => 'Analise os eventos gerados pelo IDS e classifique cada ocorrência, descreva o que pode tê-la causado, avalie o nível de severidade, indique se há indícios de atividade maliciosa e descrevad de forma clara como tratar esse evento e o que deve ser feito para evitar que ele aconteça novamente no futuro.',
            'orderby' => '9',
            'active' => 1
        ]);
        system_setting::create([
            'attribute' => 'all_events',
            'title' => 'Ativar Recebimento de Todos os Eventos.',
            'description' => 'Quando ativado, o sistema passa a aceitar todos os eventos enviados, incluindo tanto detecções de intrusão quanto eventos normais.',
            'type' => 'YesNo',
            'value' => 'No',
            'orderby' => '3',
            'active' => 1
        ]);
        system_setting::create([
            'attribute' => 'use_smart_detector_ia',
            'title' => 'Usar IA Para Classificar Eventos Dentro do SmartDetector (BETA)',
            'description' => 'Quando esta opção estiver ativada, a classificação dos eventos pela IA será realizada pelo SmartDetector, e não pelo seu IDS. Obs.: Essa opção apenas funcionará se a opção "Ativar Recebimento de Todos os Eventos." estiver ativada.',
            'type' => 'YesNo',
            'value' => 'No',
            'orderby' => '4',
            'active' => 0
        ]);
        system_setting::create([
            'attribute' => 'select_language',
            'title' => 'Selecione a Linguagem do Sistema',
            'description' => 'Selecione a linguagem padrão do sistema.',
            'type' => 'picklist',
            'picklist' => 'languages',
            'value' => 'pt_BR',
            'orderby' => '5',
            'active' => 1
        ]);
    }
}
