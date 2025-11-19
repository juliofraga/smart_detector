# 🔐 Smart Detector – Sistema de Detecção Inteligente de Ameaças em Aplicações Web

O **Smart Detector** é um sistema desenvolvido para exibir em tempo real eventos de possíveis anomalias detectadas por um IDS (Intrusion Detection System). Além do monitoramento, o sistema permite o armazenamento e consulta desses eventos, oferecendo também recursos de gestão de usuários, controle de acessos e cadastro de LLM's que serão utilizadas no seu IDS.

## 📌 Objetivos do Projeto
- Exibir eventos de possíveis anomalias em tempo real, provenientes de um IDS.
- Armazenar eventos em banco de dados para consultas futuras.
- Permitir consulta detalhada de eventos passados.
- Oferecer gestão de usuários, perfis e permissões de acesso.
- Gerenciamento de LLM's utilizada pelo IDS.

## 🎯 Escopo
✔️ Painel web para exibição em tempo real dos eventos  
✔️ Armazenamento e consulta de eventos passados  
✔️ Módulo de gestão de usuários, LLM's, perfis e permissões

## ⚙️ Tecnologias Utilizadas
- **Frontend**: Vue.js (2.7.16)  
- **Backend**: PHP 7.3.26 / Laravel 8.83.29  
- **Banco de Dados**: MySQL (MariaDB 10.4.17)

## 🛡️ Funcionalidades Principais
- Cadastro, edição e exclusão de usuários  
- Perfis de usuário (Administrador / Usuário)  
- Autenticação JWT + Logout  
- Exibição em tempo real de eventos (até 100 últimos)  
- Consulta de eventos com filtros e paginação  
- Consulta detalhada de evento  
- Cadastro e gestão de classificações de risco, LLM'S e tipos de ameaça  

## 📘 Documentação da API
Para conhecer todos os endpoints, formatos de resposta e exemplos de uso:

🔗 **Documentação da API:** https://github.com/juliofraga/smart_detector/wiki/Documenta%C3%A7%C3%A3o-da-API