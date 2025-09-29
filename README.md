# 🔐 Smart Detector – Sistema de Detecção Inteligente de Ameaças em Aplicações Web

O **Smart Detector** é um sistema desenvolvido para exibir em tempo real eventos de possíveis anomalias detectadas por um IDS (Intrusion Detection System). Além do monitoramento, o sistema permite o armazenamento e consulta desses eventos, oferecendo também recursos de gestão de usuários e controle de acessos.

## 📌 Objetivos do Projeto
- Exibir eventos de possíveis anomalias em tempo real, provenientes de um IDS.
- Armazenar eventos em banco de dados para consultas futuras.
- Permitir consulta detalhada de eventos passados.
- Oferecer gestão de usuários, perfis e permissões de acesso.

## 🎯 Escopo
✔️ Painel web para exibição em tempo real dos eventos  
✔️ Armazenamento e consulta de eventos passados  
✔️ Módulo de gestão de usuários, perfis e permissões

## ⚙️ Tecnologias Utilizadas
- **Frontend**: Vue.js (2.7.16)  
- **Backend**: PHP 7.3.26 / Laravel 8.83.29  
- **Banco de Dados**: MySQL (MariaDB 10.4.17)  
- **Segurança**: Autenticação JWT, senhas criptografadas, rate limiting no login  
- **Outros**: Postman, MySQL Workbench, Astah UML, VS Code, XAMPP  

## 📂 Estrutura de Diretórios
/frontend -> Código Vue.js
/backend -> Código Laravel (API, autenticação, serviços)
/database -> Scripts e modelos do banco de dados

## 🛡️ Funcionalidades Principais
- Cadastro, edição e exclusão de usuários  
- Perfis de usuário (Administrador / Usuário)  
- Autenticação JWT + Logout  
- Exibição em tempo real de eventos (até 100 últimos)  
- Consulta de eventos com filtros e paginação  
- Consulta detalhada de evento  
- Cadastro e gestão de classificações de risco e tipos de ameaça  

## 📋 Requisitos Não Funcionais
- Senhas armazenadas com hash  
- Token JWT com expiração de 2h  
- Bloqueio após 5 tentativas falhas de login (rate limiting)  
- Latência máxima de 10s para exibição de eventos  
- Interface responsiva para desktop, tablet e mobile